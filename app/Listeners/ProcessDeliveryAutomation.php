<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Services\DeliveryAutomationService;
use Illuminate\Support\Facades\Log;

class ProcessDeliveryAutomation
{

    public function __construct(
        private DeliveryAutomationService $automationService
    ) {}

    /**
     * Handle the event
     */
    public function handle(OrderStatusChanged $event): void
    {
        Log::info('=== DELIVERY AUTOMATION LISTENER TRIGGERED ===', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'previous_status' => $event->previousStatus,
            'new_status' => $event->newStatus
        ]);
        
        // Only process when order becomes approved
        if ($event->newStatus !== 'approved') {
            Log::info('Skipping automation - order not approved', [
                'order_id' => $event->order->id,
                'status' => $event->newStatus
            ]);
            return;
        }

        Log::info('Processing delivery automation for approved order', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number
        ]);

        try {
            $results = $this->automationService->processOrderDeliveries($event->order);
            
            Log::info('Delivery automation completed', [
                'order_id' => $event->order->id,
                'results' => $results
            ]);
        } catch (\Exception $e) {
            Log::error('Delivery automation failed for order ' . $event->order->order_number, [
                'order_id' => $event->order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Don't fail the job - automation errors shouldn't block order processing
        }
    }

}
