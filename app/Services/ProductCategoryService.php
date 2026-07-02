<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\ProductCategory;


class ProductCategoryService
{
    public function getProductsCategories(int $perPage = 10): LengthAwarePaginator
    {
        return ProductCategory::paginate($perPage);
    }
    public function createProductCategory(array $data)
    {
        return DB::transaction(function () use ($data) {
            ProductCategory::create($data);
        });
    }
    public function updateProductCategory(ProductCategory $productCategory, array $data, )
    {
        return DB::transaction(function () use ($data, $productCategory) {
            $productCategory->update($data);
        });
    }
    public function softDelete(int $id): bool
    {
        $productCategory = ProductCategory::find($id);
        if (!$productCategory) {
            return false;
        }
        $productCategory->delete();
        return true;
    }
}