<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendVerificationEmailJob;
use App\Mail\NewVerificationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function register()
    {
        $validatedData = [
            'name'=>'morteza',
            'email'=>'morteza167@gmail.com',
            'password'=>12354687654
        ];

        $user = User::whereEmail('morteza167@gmail.com')->first();
        SendVerificationEmailJob::dispatch($user);

    }
}
