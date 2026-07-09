<?php

namespace App\Listeners;

use App\Events\ProductCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RestoreOrderItems
{


    public function handle(ProductCancelled $event): void
    {
        $orderItem = $event->orderItem;
        $product = $orderItem->product;

        if ($product && !$orderItem->getStatus()) {
            $product->decrement('stock', $orderItem->quantity);
        }

    }
}
