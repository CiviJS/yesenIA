<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use App\Services\ClientService;
use App\Services\ProductService;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected ClientService $clientService,
        protected ProductService $productService
    ) {
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


            if (url()->previous() === url()->current()) {
                return redirect()->route('dashboard')->withErrors(['error' => 'Hubo un problema al cargar las deudas.']);
            }

            return back()->withErrors(['error' => 'Hubo un problema al cargar las deudas.']);
        }
    }
    public function detail(Order $order)
    {
        $orderWithPayments = $order->load('payments');
        return view('pages.orders.detail', compact('order'));
    }

    public function create()
    {

        return view('pages.orders.create', [
            'clients' => $this->clientService->getClients(100),
            'products' => $this->productService->getProducts()
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $response = $this->orderService->createOrder($request->validated());
            if (!$response) {
                return redirect()->back()->withErrors(['error' => 'Producto no tiene Suficiente stock']);
            }
            return redirect()->route('orders.index')->with('success', 'Deuda registrada correctamente.');
        } catch (Exception $e) {
            Log::error('Fallo critico al registrar la deuda', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);

            return redirect()->back()->withInput()->withErrors(['error' => 'Ocurrió un problema al registrar la deuda.']);
        }
    }

    //para reestockear los products del orderitem osea la deuda
    public function restoreOrderItem(OrderItem $orderItem)
    {
        try {
            $this->orderService->restoreOrderItem($orderItem);

            return redirect()->back()->with('success', 'Producto restaurado correctamente.');
        } catch (Exception $e) {
            Log::error('Error al restaurar producto: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ocurrió un problema al restaurar el producto.']);
        }
    }

    public function cancel(Order $order)
    {
        try {

            if (!$order->getStatus()) {
                return redirect()->route('orders.index')->with('error', 'La deuda ya estaba cancelada o no existe.');
            }
            $this->orderService->cancelOrder($order);

            return redirect()->route('orders.index')->with('success', 'Deuda cancelada correctamente.');
        } catch (Exception $e) {
            Log::error("Error al cancelar deuda ID {$order->id}: " . $e->getMessage(), [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);

            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');
        }
    }

    //para restaurar los productos del order item
    public function cancelOrderItem(OrderItem $orderItem)
    {
        try {
            if (!$orderItem->getStatus()) {
                return redirect()->back()->withErrors(['error' => 'El producto ya está cancelado.']);
            }

            $this->orderService->cancelOrderItem($orderItem);
            return back()->with('success', 'Producto cancelado correctamente.');
        } catch (Exception $e) {
            Log::error('Error al cancelar producto: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error inesperado.']);
        }
    }



    public function restore(Order $order)
    {
        try {
            //si la deuda esta activa
            if ($order->getStatus()) {
                return redirect()->route('orders.index')->with('error', 'La deuda no se puede restaurar en este momento.');
            }
            $this->orderService->restoreOrder($order);

            return redirect()->route('orders.index')->with('success', 'Deuda restaurada correctamente.');
        } catch (Exception $e) {
            Log::error("Error al restaurar deuda ID {$order->id}: " . $e->getMessage(), [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);

            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');
        }
    }
}
