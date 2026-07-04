<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        try {
            $orders = $this->orderService->getOrders();

            return view('pages.orders.index', compact('orders'));
        } catch (Exception $e) {
            Log::error('Fallo critico al cargar las deudas', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);

            return back()->withErrors(['error' => 'Hubo un problema al cargar las deudas.']);
        }
    }

    public function create()
    {
        $clients = app(\App\Services\ClientService::class)->getClients(100);
        $products = app(\App\Services\ProductService::class)->getProducts();

        return view('pages.orders.create', compact('clients', 'products'));
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $this->orderService->createOrder($request->validated());

            return redirect()->route('orders.index')->with('success', 'Deuda registrada correctamente.');
        } catch (Exception $e) {
            Log::error('Fallo critico al registrar la deuda', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);

            return back()->withInput()->withErrors(['error' => 'Ocurrió un problema al registrar la deuda.']);
        }
    }

    public function cancel(Order $order)
    {
        try {
            $isCanceled = $this->orderService->cancelOrder($order);

            if (! $isCanceled) {
                return redirect()->route('orders.index')->with('error', 'La deuda ya estaba cancelada o no existe.');
            }

            return redirect()->route('orders.index')->with('success', 'Deuda cancelada correctamente.');
        } catch (Exception $e) {
            Log::error("Error al cancelar deuda ID {$order->id}: " . $e->getMessage(), [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);

            return back()->with('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');
        }
    }

    public function restore(Order $order)
    {
        try {
            $isRestored = $this->orderService->restoreOrder($order);

            if (! $isRestored) {
                return redirect()->route('orders.index')->with('error', 'La deuda no se puede restaurar en este momento.');
            }

            return redirect()->route('orders.index')->with('success', 'Deuda restaurada correctamente.');
        } catch (Exception $e) {
            Log::error("Error al restaurar deuda ID {$order->id}: " . $e->getMessage(), [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);

            return back()->with('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');
        }
    }
}
