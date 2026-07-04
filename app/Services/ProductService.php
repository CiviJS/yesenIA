<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function getProducts(int $perPage = 10): LengthAwarePaginator
    {
        return Product::with('category')
            ->latest()
            ->paginate($perPage);
    }
    public function createProduct(array $data)
    {
        return DB::transaction(function () use ($data) {
            Product::create($data);
        });
    }

    public function softDelete(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return false;
        }
        
        $product->delete();
        return true;
    }
    public function updateProduct(array $data, Product $product)
    {
        return DB::transaction(function () use ($data, $product) {
            $product->update($data);
        });
    }


}