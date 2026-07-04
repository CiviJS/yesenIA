<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Services\SaleService;
use App\Services\ProductService;
use Exception;
use Illuminate\Support\Facades\Log;
use Mockery\Expectation;
class SaleController extends Controller
{
    protected $saleService;
    protected $productService;
    public function __construct(SaleService $saleService, ProductService $productService)
    {
        $this->saleService = $saleService;
        $this->productService = $productService;
    }
    public function index()
    {
        try {
            $sales = $this->saleService->GetSales();


            return view('pages.sales.index', compact('sales'));

        } catch (\Exception $e) {
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
            return redirect()->route('sales.index')->with('success', 'Venta registrada correctamente');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'hubo un problema al crear al registrar la venta']);
        }
    }
    public function softDelete($id)
    {
        try {
            $isDeleted = $this->saleService->softDelete($id);
            if (!$isDeleted) {
                return redirect()->route('sales.index')->with('error', 'la venta ya se elimino o ya no existe');
            }
            return redirect()->route('sales.index')->with('success', 'venta cancelada correctamente');
        } catch (Exception $e) {
            \Log::error("Error al procesar venta ID $id: " . $e->getMessage()); // Registro interno
            return back()->with('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');

        }
    }

    public function restore($id)
    {
        try {
            $isDeleted = $this->saleService->restore($id);
            if (!$isDeleted) {
                return redirect()->route('sales.index')->with('error', 'la venta ya se elimino o ya no existe');
            }
            return redirect()->route('sales.index')->with('success', 'venta cancelada correctamente');
        } catch (Exception $e) {
            \Log::error("Error al procesar venta ID $id: " . $e->getMessage()); // Registro interno
            return back()->with('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');

        }
    }



}
