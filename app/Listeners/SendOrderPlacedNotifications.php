<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\AdminNewOrderMail;
use App\Mail\OrderPlacedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderPlacedNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderPlaced $event): void
    {
        $order = $event->order->loadMissing('items.product');

        try {
            if (!empty($order->customer_email)) {
                Mail::to($order->customer_email)->queue(new OrderPlacedMail($order));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to queue user order placed email', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
            $adminRecipients = collect(explode(',', (string) config('services.admin.emails', '')))
                ->map(fn ($email) => trim($email))
                ->filter()
                ->values()
                ->all();

            if (empty($adminRecipients) && config('services.admin.email')) {
                $adminRecipients = [config('services.admin.email')];
            }

            if (!empty($adminRecipients)) {
                Mail::to($adminRecipients)->queue(new AdminNewOrderMail($order));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to queue admin new order email', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

