<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\testNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;

class testNotificationController extends Controller
{
    public function index()
    {
        $user = User::where('email','maleki.f.acc@gmail.com')->first();
        $key = "notif:testNotification:user:{$user->id}";
        $allowed = RateLimiter::attempt($key, $maxAttempts=1, function() use($user){
            $user->notify(new testNotification($user->id));
        }, 120);
        if(!$allowed)
        {
            return 'please try again later';
        }
        return 'Email sent.';
    }
}
