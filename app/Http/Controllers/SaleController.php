<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Services\SaleService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;
class SaleController extends Controller
{
    protected $saleService;
    protected $productService;
    public function __construct(SaleService $saleService , ProductService $productService)
    {
        $this->saleService = $saleService;
        $this->productService = $productService;
    }
    public function index()
    {
        try{
            $sales = $this->saleService->GetSales();
            return view('pages.sales.index', compact('sales'));

        }catch( \Exception $e){
                Log::error('Fallo critico en el registro', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);
        }
    }
    public function create()
    {
        $products = $this->productService->getProducts();
        return view('pages.sales.create', compact('products'));
    }

    public function store(StoreSaleRequest $request)
    {
        try {
            $this->saleService->createSale($request->validated());
            return redirect('Sale.index')->with('success', ['message' => 'Venta registrada correctamente']);
        }catch (\Exception $e) {
            return redirect('sale.create')->with('error', ['message' => $e->getMessage()]);
        }
    }
}
