<?php

namespace App\Http\Controllers;

use App\Notifications\testSlackNotification;
use Illuminate\Support\Facades\Notification ;

class SlackController extends Controller
{
    public function sendSlack()
{
    $orderId = rand(1111,9999);
    $status  = 'delayed';
    $reason  = 'External payment gateway lag';
    $channel = config('services.slack.notifications.channel');
    // روش سراسری
    Notification::route('slack', $channel)
        ->notify(new testSlackNotification($orderId, $status, $reason));

    return 'Slack notification queued.';
}
}
