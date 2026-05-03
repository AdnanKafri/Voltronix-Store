<?php

namespace App\Listeners;

use App\Events\DeliveryCreated;
use App\Mail\DeliveryReadyMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDeliveryReadyNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(DeliveryCreated $event): void
    {
        $delivery = $event->delivery->loadMissing('order.items.product');
        $order = $delivery->order;

        if (!$order || empty($order->customer_email)) {
            return;
        }

        try {
            Mail::to($order->customer_email)->queue(new DeliveryReadyMail($delivery));
        } catch (\Throwable $e) {
            Log::warning('Failed to queue delivery ready email', [
                'delivery_id' => $delivery->id,
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

