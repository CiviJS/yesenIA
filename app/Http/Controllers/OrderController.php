<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
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


            if (url()->previous() === url()->current()) {
                return redirect()->route('dashboard')->withErrors(['error' => 'Hubo un problema al cargar las deudas.']);
            }

            return back()->withErrors(['error' => 'Hubo un problema al cargar las deudas.']);
        }
    }
    public function detail(Order $order)
    {
        return view('pages.orders.detail', compact('order'));
    }

    public function create()
    {

        //ojo con esto xd , convertirlo a inyecicon de dependencias 
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

            return redirect()->back()->withInput()->withErrors(['error' => 'Ocurrió un problema al registrar la deuda.']);
        }
    }

    //para reestockear los products del orderitem osea la deuda
    public function restoreOrderItem(OrderItem $orderItem)
    {
        try {
            $this->orderService->restoreOrderItem($orderItem);
            return redirect()->back()->with('sucess' , 'producto cancelado correctamente');
        } catch (Exception $e) {
            Log::error('error al cancelar producto de la deuda');
            return redirect()->back()->withInput()->withErrors(['error' => 'Ocurrió un problema al cancelar un producto de la deuda.']);

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
            if ($orderItem->getStatus()) {
                return redirect()->route('orders.index')->with('errors', 'La deuda no se puede restaurar en este momento.');
            }

            $this->orderService->cancelOrderItem($orderItem);
            return back()->with('success' , 'producto restaurado correctamente');
        } catch (Exception $e) {
            Log::error("Error al restaurar deuda ID {$orderItem->id}: " . $e->getMessage(), [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);

            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Inténtalo de nuevo.');
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
