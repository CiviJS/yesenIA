<?php

namespace App\Listeners;

use App\Events\ProductCancelled;

class IncrementProductStock
{


    /**
     * Handle the event.
     */
    public function handle(ProductCancelled $event): void
    {
        $orderItem = $event->orderItem;
       if($orderItem->getStatus()){
        $orderItem = $event->orderItem;
       }
         \Log::info('producto devuelto' . $orderItem);

        $orderItem->product()->increment('stock', $orderItem->quantity);
    }
}
