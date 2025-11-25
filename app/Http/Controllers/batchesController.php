<?php

namespace App\Http\Controllers;

use App\Jobs\campainJob;
use App\Jobs\VideoConvertorJob;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class batchesController extends Controller
{
   public function batch()
      {
        $campaign = Bus::findBatch('a07122c3-b30a-4bd0-b320-87264c5c05ee');
        // dd($campaign->progress());
        $campaign->cancel();

        $path = '/storage/12.mp4';
        $jobs = collect();
        $users = User::all();
        foreach ($users->take(40) as $user) {
            $jobs->push(new campainJob($user));
        }

        $bachData = Bus::batch([
            ...$jobs,
            new VideoConvertorJob($path),
            function (){
            Log::info('chain is run!');
        }
        ])->onQueue('notifications')
          ->catch(function(Batch $batch, Throwable $e){
            Log::info('Batch failed', [
                'batch_id'      => $batch->id,
                'total_jobs'    => $batch->totalJobs,
                'failed_jobs'   => $batch->failedJobs,
                'failed_ids'    => $batch->failedJobIds,
                'processed'     => $batch->processedJobs(),
                'progress'      => $batch->progress(),
                'exception'     => $e->getMessage(),
            ]);
        })
        ->allowFailures()
        ->name('kampein yalda')
        ->dispatch();

        return $bachData;

      }
}
