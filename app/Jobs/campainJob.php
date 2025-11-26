<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\yaldaNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

class campainJob implements ShouldQueue
{
    use Queueable, Batchable;

    public int $tries = 1;
    // public function backoff()
    // {
    //     return [60,300,900];
    // }
    public function retryUntil()
    {
        return now()->addMinutes(30);
    }


    /**
     * Create a new job instance.
     */
    public function __construct(public User $user,public int $key=1)
    {
        //
    }
    public function middleware()
    {
        return [new SkipIfBatchCancelled];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(3);
        $this->user->notify(new yaldaNotification($this->key));
        // if ($this->attempts()< $this->tries){
        //     $this->release(now()->addMinute(2));
        // }
    }

    // public function tags(): array
    // {
    //     return [
    //         "campaign:yalda",
    //         "batch:{$this->key}",
    //         "user:{$this->user->id}",
    //     ];
    // }
}
