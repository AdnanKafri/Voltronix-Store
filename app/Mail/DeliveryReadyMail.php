<?php

namespace App\Mail;

use App\Models\OrderDelivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeliveryReadyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public OrderDelivery $delivery)
    {
    }

    public function build(): self
    {
        return $this
            ->subject(__('emails.delivery_ready.subject', ['order_number' => $this->delivery->order->order_number]))
            ->view('emails.orders.delivery-ready', [
                'delivery' => $this->delivery,
                'order' => $this->delivery->order,
            ]);
    }
}

