<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\SlackMessage;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;

class testSlackNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $orderId, public string $status, public ?string $reason = null) {}

    public function via($notifiable): array
    {
        return ['slack'];
    }

    public function toSlack($notifiable): SlackMessage
    {
        $channel = config('services.slack.notifications.channel');
        return (new SlackMessage)
            ->to($channel) // یا از config/services بگیر
            ->text("⚠️ Order #{$this->orderId} {$this->status}")
            ->headerBlock("Order #{$this->orderId}")
            ->sectionBlock(function (SectionBlock $b) {
                $b->field("*Status:*\n{$this->status}")->markdown();
                $b->field("*Reason:*\n".($this->reason ?? '—'))->markdown();
                $b->field("*Link:*\n".url("/orders/{$this->orderId}"))->markdown();
            });
    }
}
