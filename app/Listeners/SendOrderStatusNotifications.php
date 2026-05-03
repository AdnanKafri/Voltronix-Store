<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Mail\OrderApprovedMail;
use App\Mail\OrderCancelledMail;
use App\Mail\OrderRejectedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order->loadMissing('items.product');

        if (empty($order->customer_email)) {
            return;
        }

        try {
            match ($event->newStatus) {
                'approved' => Mail::to($order->customer_email)->queue(new OrderApprovedMail($order)),
                'rejected' => Mail::to($order->customer_email)->queue(new OrderRejectedMail($order)),
                'cancelled' => Mail::to($order->customer_email)->queue(new OrderCancelledMail($order)),
                default => null,
            };
        } catch (\Throwable $e) {
            Log::warning('Failed to queue order status email', [
                'order_id' => $order->id,
                'status' => $event->newStatus,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

