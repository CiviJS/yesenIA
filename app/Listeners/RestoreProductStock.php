<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Support\Facades\Log;

class RestoreProductStock
{
    public function handle(OrderCancelled $event): void
    {
        $order = $event->orderable->load('items.product');

        if (! empty($order->is_canceled)) {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            Log::info('Operación cancelada, productos restaurados: ' . get_class($event->orderable) . ' ID: ' . $event->orderable->id);
        }
    }
}
