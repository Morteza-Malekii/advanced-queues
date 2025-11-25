<?php

namespace App\Http\Controllers;

use App\Jobs\campainJob;
use App\Models\User;
use App\Notifications\yaldaNotification;
use Illuminate\Http\Request;

class CampainController extends Controller
{
    public function yalda()
    {
        $users = User::all();
        $user_totalGroups = $users->chunk(50);
        foreach($user_totalGroups as $key=>$user_group)
        {
            foreach($user_group as $user)
            {
                // $user->notify(new yaldaNotification($key));
                campainJob::dispatch($user,$key);
            }
        }
    }
}
