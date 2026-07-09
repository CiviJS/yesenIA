<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Support\Facades\Log;
use App\Events\ProductRestored;
class DecrementProductStock
{
    public function handle(ProductRestored $event): void
    {
     
        $orderItem = $event->orderItem;
            \Log::info('producto descontado' . $orderItem);
        $orderItem->product()->decrement('stock', $orderItem->quantity);
    }
}
