<?php

namespace App\Listeners;

use App\Events\OrderRestored;
use Illuminate\Support\Facades\Log;

class RestoreOrder
{
    public function handle(OrderRestored $event): void
    {
        $orderable = $event->orderable->load('items.product');
      
        if (!$orderable->trashed()) {
            foreach ($orderable->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }
        }
        Log::info('Operación restaurada: ' . get_class($event->orderable) . ' ID: ' . $event->orderable->id);
    }
}
