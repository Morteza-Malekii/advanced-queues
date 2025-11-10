<?php

namespace App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class testNotification extends Notification implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $userName)
    {
        $this->onQueue('notif-queue');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to My App')
            ->greeting('saaalaaam!'.$this->userName)
            ->markdown('mail.notification.testMarkDown');
            // ->line('The introduction to the notification.')
            // ->action('Notification Action', url('/'));
            // ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message'  => "User #{$this->userName} khosh galdin",
        ];
    }

    public function shouldSend($notifiable, $channel)
    {
        if($channel !== 'mail') return true;
        $key = sprintf('notif:%s:%s:user:%s', static::class, $channel, $notifiable->id);

        // if(RateLimiter::tooManyAttempts($key, 1))
        // {
        //     return false;
        // }
        // RateLimiter::hit($key, 2*60);
        // return true;

        return RateLimiter::attempt($key, $maxAttempts = 2, function(){}, $decaySeconds = 120);
    }
}
