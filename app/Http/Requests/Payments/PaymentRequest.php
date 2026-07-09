<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
        'order_id' => 'required|integer|exists:orders,id',
        'amount' => ['required', 'numeric', 'min:1', 'decimal:0,2', 'max:' . $this->order->total_amount],
        'payment_method' => 'required|string|max:50',
        ];

    }
}
