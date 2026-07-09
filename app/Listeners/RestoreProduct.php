<?php

namespace App\Listeners;

use App\Events\ProductCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RestoreProduct
{


    /**
     * Handle the event.
     */
    public function handle(ProductCancelled $event): void
    {
        $orderItem = $event->orderItem;
        $product = $orderItem->product;
     
        if ($product && !$orderItem->getStatus()) {
            $product->increment('stock', $orderItem->quantity);
        }

    }
}
