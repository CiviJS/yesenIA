<?php

namespace App\Http\Controllers;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\ProductCategoryService;
use Exception;


class ProductController extends Controller
{
    protected $productCategoryService;
    private $productService;
    public function __construct(ProductService $productService, ProductCategoryService $productCategoryService)
    {
        $this->productService = $productService;
        $this->productCategoryService = $productCategoryService;
    }
    public function index()
    {
        $products = $this->productService->getProducts();
        $categories = $this->productCategoryService->getProductsCategories();

        return view('pages.products.index', compact('products', 'categories'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $this->productService->createProduct($request->validated());
            return redirect()->route('products.index')->with('success', 'Producto Creado Correctamente');
        } catch (Exception $e) {
            return  back()->withInput()->withErrors(['error' => 'Hubo un problema al crear el producto']);
        }
    }
    public function edit (Product $product){
        $productCategories = $this->productCategoryService->getProductsCategories();
        return view('pages.products.edit', compact('product', 'productCategories'));
    }
    public function update(UpdateProductRequest $request, Product $product){
        try{
            $this->productService->updateProduct($request->validated(), $product);
            return redirect()->route('products.index')->with('success', 'producto actualizado correctamente');
        }catch(Exception $e){
            return back()->withInput()->withErrors(['error' => 'Hubo un problema al actualizar el producto']);
        }
    }

    public function softDelete($id)
    {
        try {
            $isDeleted = $this->productService->softDelete($id);
            if (!$isDeleted) {
                return redirect()->route('products.index')->with('error', 'el producto ya fue eliminado o no existe');
            }
                return redirect()->route('products.index')->with('success', 'el producto eliminado con exito');
        }catch(Exception $e){
            return redirect()->route('products.index')->with('error', 'Hubo un problema al eliminar un producto');

        }
    }
}
