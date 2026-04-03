<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\OrderConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmation implements ShouldQueue
{
    use InteractsWithQueue;
    
    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $order = $event->order;
        
        // Send email to customer
        Mail::to($order->customer_email)
            ->send(new OrderConfirmationMail($order));
            
        // TODO: Uncomment when admin notification is ready
        // Mail::to(config('mail.admin_email'))
        //     ->send(new NewOrderNotification($order));
    }
}
