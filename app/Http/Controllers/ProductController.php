<?php

namespace App\Http\Controllers;
use App\Http\Requests\Product\StoreProductRequest;
use App\Services\ProductService;
use App\Services\ProductCategoryService;
use Exception;


class ProductController extends Controller
{       
    protected $productCategoryService;
    private $productService;
    public function __construct(ProductService $productService, ProductCategoryService $productCategoryService){
        $this->productService = $productService;
        $this->productCategoryService = $productCategoryService;
    }
    public function index()
    {
        $products = $this->productService->getProducts();
        $categories = $this->productCategoryService->getProductsCategories();
           
        return view('pages.products.index', compact('products', 'categories'));
    }

    public function store(StoreProductRequest $request) {
        try{
        $this->productService->createProduct($request->validated());
        return redirect()->route('products.index')->with('success', 'Producto Creado Correctamente');
        }catch (Exception $e) {
        return redirect()->route('products.index')->with('error', 'Hubo un problema al crear el producto');
        }

    }
}
