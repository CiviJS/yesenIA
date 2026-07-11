<?php

namespace App\Listeners;

use App\Events\OrderCancelled;

use Illuminate\Support\Facades\Log;

class CancelOrder
{


    public function handle(OrderCancelled $event): void
    {
        $orderable = $event->orderable->load('items.product');
        

        if ($orderable->getStatus()) {
            foreach ($orderable->items as $item) {
                $item->product->increment('stock', $item->quantity);
                $item->delete();
            }
        }

    }
}
