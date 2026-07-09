<?php

namespace App\Services;

use App\Events\OrderCancelled;
use App\Events\OrderRestored;
use App\Events\ProductCancelled;
use App\Events\ProductRestored;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;


class OrderService
{
    public function getOrders(int $perPage = 10): LengthAwarePaginator
    {
        return Order::with(['client', 'items.product'])
            ->withTrashed()
            ->latest()
            ->paginate($perPage);
    }

    public function restoreOrderItem(OrderItem $orderItem)
    {
        return DB::transaction(function () use ($orderItem) {
            $orderItem->restore();
            ProductCancelled::dispatch($orderItem);
        });
    }

    public function cancelOrderItem(OrderItem $orderItem)
    {

        return DB::transaction(function () use ($orderItem) {
            ProductRestored::dispatch($orderItem);
            $orderItem->delete();
        });
    }

    //Corregido : problema de N+1 
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {

            $order = Order::firstOrCreate(
                ['client_id' => $data['client_id']],
                ['total_amount' => 0]
            );

            $productIds = collect($data['items'])->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $newTotal = 0;

            foreach ($data['items'] ?? [] as $item) {
                $product = $products->get($item['product_id']);

                if (!$product) {
                    throw new \Exception("Producto no encontrado.");
                }

                $quantity = (int) $item['quantity'];

    
                if ($product->stock < $quantity) {
                    throw new \Exception("Stock Insuficiente para: {$product->name}");
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => (float) ($item['unit_price'] ?? $product->price),
                    'orderable_id' => $order->id,
                    'orderable_type' => Order::class,
                ]);

                $product->decrement('stock', $quantity);

                $newTotal += ($quantity * ($item['unit_price'] ?? $product->price));
            }

            
            $order->increment('total_amount', $newTotal);

            return $order;
        });
    }

    public function cancelOrder(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            $order->delete();
            OrderCancelled::dispatch($order);
            return true;
        });
    }

    public function restoreOrder(Order $order): bool
    {

        return DB::transaction(function () use ($order) {
            $order->restore();
            OrderRestored::dispatch($order);
            return true;
        });
    }
}
