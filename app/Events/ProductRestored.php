<?php

namespace App\Events;

use App\Models\OrderItem;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductRestored
{
    use Dispatchable, SerializesModels;

    public OrderItem $orderItem;


    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
    }
}
