<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Jobs\MonitorPendingOrder;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $order = Order::create([
            'user_id'=> 14,
            'status'=>OrderStatus::PENDING,
            'price'=>1000000
        ]);

        MonitorPendingOrder::dispatch($order,)->delay(20);

    }
}
