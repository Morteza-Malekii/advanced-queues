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
        $path = '/storage/12.mp4';
        $jobs = collect();
        $users = User::all();
        foreach ($users->take(20) as $user) {
            $jobs->push(new campainJob($user));
        }

        Bus::batch([
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
                // اگر خواستی:
                // 'trace'      => $e->getTraceAsString(),
            ]);
        })
        ->dispatch();
      }
}
