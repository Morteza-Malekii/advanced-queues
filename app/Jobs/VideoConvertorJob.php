<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VideoConvertorJob implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $path)
    {
        $this->onQueue('video-convert');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('this is a vedeo convrtor! video located in '.$this->path);
    }
}
