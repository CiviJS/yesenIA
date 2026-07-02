<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|max:100|string',
            'product_category_id' => 'required|integer|exists:product_categories,id',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0'
        ];
    }
}
