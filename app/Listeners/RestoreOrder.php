<?php

namespace App\Listeners;

use App\Events\OrderRestored;
use Illuminate\Support\Facades\Log;

class RestoreOrder
{
    public function handle(OrderRestored $event): void
    {
        $orderable = $event->orderable->load('items.product');

        if (!$orderable->getStatus()) {
            foreach ($orderable->items as $item) {

                $item->product->decrement('stock', $item->quantity);
                $item->restore();
            }
        }
    }
}
