<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Jobs\MonitorPendingOrder;
use App\Jobs\SendWebHook;
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

        // MonitorPendingOrder::dispatch($order,)->delay(20);


        SendWebHook::dispatch('https://webhook.site/f2c0aa32-a9e3-4f02-ae1e-0b4575b243ec', [
            'price' => 1000,
            'name'=> 'morteza maleki'
        ],$order)->delay(now()->addSecond(30));
    }
}
