<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\testNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class testNotificationController extends Controller
{
    public function index()
    {
        $user = User::where('email','maleki.f.acc@gmail.com')->first();
        $user->notify(new testNotification($user->name));
        return 'Email sent.';
    }
}
