<?php

namespace App\Services;

use App\Events\OrderCancelled;
use App\Events\OrderRestored;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function getOrders(int $perPage = 10): LengthAwarePaginator
    {
        return Order::with(['client', 'items.product'])->latest()->paginate($perPage);
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'client_id' => $data['client_id'],
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($data['items'] ?? [] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];
                $unitPrice = (float) ($item['unit_price'] ?? $product->price);

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'orderable_id' => $order->id,
                    'orderable_type' => Order::class,
                ]);

                $totalAmount += ($quantity * $unitPrice);
            }

            $order->forceFill(['total_amount' => $totalAmount])->save();

            return $order;
        });
    }

    public function cancelOrder(Order $order): bool
    {
        if ((bool) $order->is_canceled) {
            return false;
        }

        return DB::transaction(function () use ($order) {
            $order->forceFill(['is_canceled' => true])->save();
            OrderCancelled::dispatch($order);

            return true;
        });
    }

    public function restoreOrder(Order $order): bool
    {
        if (! (bool) $order->is_canceled) {
            return false;
        }

        return DB::transaction(function () use ($order) {
            $order->forceFill(['is_canceled' => false])->save();
            OrderRestored::dispatch($order);
            return true;
        });
    }
}
