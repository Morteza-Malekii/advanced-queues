<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VideoConvertorJob implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable, SerializesModels, Batchable;
    public $tries = 2;
    /**
     * Create a new job instance.
     */
    public function __construct(public string $path)
    {
        $this->onQueue('video-convert');
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
        try {
            throw new Exception();
            Log::info('this is a vedeo convrtor! video located in '.$this->path);
        } catch (\Throwable $e) {
            if($this->attempts() < 3){
                $this->release(10);
            }
        }
    }
}
