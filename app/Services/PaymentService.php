<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Order;
use Log;

class PaymentService {

    public function pay(Order $order, array $payment){

        return DB::transaction(function () use ($order, $payment){
            Payment::create($payment);
            Log::info('descontando ' . $payment['amount'] . ' a ' . $order->total_amount  );
            $order->decrement('total_amount' , $payment['amount']);
        });
    }

}