<?php

namespace App\Jobs;

use App\Mail\NewVerificationEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmailJob implements ShouldQueue
{
    use Queueable;

    public $tries = 5;
    public $backoff = [60,60*10,60*60];

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        $this->onQueue('sending-verification');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user)->send(new NewVerificationEmail($this->user));
    }
}
