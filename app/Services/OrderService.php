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
        return Order::with(['client', 'items.product'])
            ->withTrashed() 
            ->latest()
            ->paginate($perPage);
    }
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::where('client_id', $data['client_id'])->first();

            if(!$order){
                $order = Order::create([
                'client_id' => $data['client_id'],
                'total_amount' => 0,
            ]);
            }

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
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock Insuficiente para: {$product->name}");
                }
                $product->decrement('stock', $quantity);

                $totalAmount += ($quantity * $unitPrice);
            }

            $order->forceFill(['total_amount' => $totalAmount])->save();

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
