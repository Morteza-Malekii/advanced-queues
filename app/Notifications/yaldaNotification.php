<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class yaldaNotification extends Notification implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable ;

    public $tries = 5;
    public function backoff()
    {
        return [60,300,900];
    }
    public function retryUntil()
    {
        return now()->addHours(2);
    }
    /**
     * Create a new notification instance.
     */
    public function __construct(public int $key)
    {
        $this->onQueue('notifications');
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function withDelay()
    {
        return [
            'mail'=> $this->key * 30 ,
            'database'=> $this->key * 30 ,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to yalda campain')
            ->greeting('there are many sweet pashmak here!')
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'yalda mobarak'
        ];
    }


}
