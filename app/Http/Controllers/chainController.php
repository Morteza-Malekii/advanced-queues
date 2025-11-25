<?php

namespace App\Http\Controllers;

use App\Jobs\campainJob;
use App\Jobs\VideoConvertorJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class chainController extends Controller
{


//    public function chain()
//    {
//     $user = User::find(3);
//     $path = '/storage/12.mp4';
//     Bus::chain([
//         new campainJob($user),
//         new VideoConvertorJob($path),
//         function (){
//             Log::info('chain is run!');
//         }
//     ])->dispatch();
//    }

      public function chain()
      {
        $path = '/storage/12.mp4';
        $jobs = collect();
        $users = User::all();
        foreach ($users as $user) {
            $jobs->push(new campainJob($user));
        }

        Bus::chain([
            ...$jobs,
            new VideoConvertorJob($path),
            function (){
            Log::info('chain is run!');
        }
        ])->onQueue('notifications')
          ->catch(function(Throwable $throwable){
            Log::info('has error');
        })
        ->dispatch();
      }
}
