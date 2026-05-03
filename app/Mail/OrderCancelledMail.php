<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCancelledMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function build(): self
    {
        return $this
            ->subject(__('emails.order_cancelled.subject', ['order_number' => $this->order->order_number]))
            ->view('emails.orders.cancelled', [
                'order' => $this->order,
            ]);
    }
}

