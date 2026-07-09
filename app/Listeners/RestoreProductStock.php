<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Support\Facades\Log;

class RestoreProductStock
{
    public function handle(OrderCancelled $event): void
    {

        $order = $event->orderable->load('items.product');
               
       
        if ($order->trashed()) {

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        
            Log::info('Operación cancelada (Soft Deleted), productos restaurados: ' . get_class($event->orderable) . ' ID: ' . $order->id);
        }
    }
}
