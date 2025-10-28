<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebHook implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $url, public $data, public Order $order)
    {
        $this->onQueue('send-webhook');
    }
    public $tries = 3;
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::post($this->url, $this->data);
        if($response->failed())
        {
            $this->release(now()->addSecond(10));
        }else
        {
            $this->order->markAsComleted();
        }
    }

    // public function retryUntil()
    // {

    // }

    public function failed()
    {
        Log::info('do somthing after send webhook failed');
        $this->order->markAsCanceled();
    }
}
