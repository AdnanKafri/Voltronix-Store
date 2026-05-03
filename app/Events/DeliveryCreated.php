<?php

namespace App\Events;

use App\Models\OrderDelivery;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public OrderDelivery $delivery)
    {
    }
}

