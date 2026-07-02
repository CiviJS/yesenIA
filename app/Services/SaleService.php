<?php

namespace App\Services;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;


class SaleService
{
    public function createSale(array $data): Sale
    {
        return
            DB::transaction(function () use ($data) {
                $sale = Sale::create([]);

                foreach ($data['items'] as $item) {
                    $product = Product::where('id', $item['product_id'])->lockForUpdate()->firstOrFail();

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stock Insuficiente para: {$product->name}");
                    }
                    $product->decrement('stock', $item['quantity']);

                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                    ]);
                }
                return $sale;
            });
    }
    public function getSales(int $perPage = 10): LengthAwarePaginator
    {
        return Sale::with('client', 'items.product')->paginate($perPage);
    }


}