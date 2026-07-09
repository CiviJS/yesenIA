<?php

namespace App\Http\Controllers;
use App\Services\PaymentService;
use App\Http\Requests\Payments\PaymentRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Exception;
class PaymentController extends Controller
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function pay(Order $order ,PaymentRequest $request)
    {
        try {
            
            $this->paymentService->pay($order, $request->validated());
            return back()->with('sucess' , 'pago hecho correctamente');

        } catch (Exception $e) {
            Log::error('error de pago ,  excepcion: ' . $e);
            return back()->withInput()->withErrors('error', 'error inesperado al momento de registrar el pago');

        }
    }
}
