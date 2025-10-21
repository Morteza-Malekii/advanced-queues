<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Mail\DiscountEmail;
use App\Mail\NewVerificationEmail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MonitorPendingOrder implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Order $order)
    {
        //
    }

    public $tries = 6;
    /**
     * Execute the job.
     */
    public function handle(): void
    {

        if (
            $this->order->status === OrderStatus::CANCELED
            || $this->order->status === OrderStatus::COMPLETED
        ) {
            return;
        }
        if ($this->order->olderThan(2)) {
            $this->order->markAsCanceled();
            return;
        }
        // send notification and sms
        $user = User::whereEmail('morteza167@gmail.com')->first();
        switch ($this->attempts()) {
            case '1':
                 Mail::to($user)->send(new NewVerificationEmail($user));
                break;
            case '2':
                Mail::to($user)->send(new DiscountEmail($user));
                break;
            case '3':
                 Mail::to($user)->send(new NewVerificationEmail($user));
                break;
            case '4':
                Mail::to($user)->send(new DiscountEmail($user));
                break;
            default:
                # code...
                break;
        }

        $this->release(30);
    }
}
