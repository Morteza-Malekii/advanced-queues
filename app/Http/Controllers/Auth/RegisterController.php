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

        //     $validatedData = [
        //         'name'=>'morteza',
        //         'email'=>"maleki".rand(10,500)."@gmail.com",
        //         'password'=>12354687654
        //     ];
        //     $user = User::create($validatedData);

        // $user = User::whereEmail('maleki.f.acc@gmail.com')->first();
        // SendVerificationEmailJob::dispatch($user);
        User::factory(100)->create();
    }
}
