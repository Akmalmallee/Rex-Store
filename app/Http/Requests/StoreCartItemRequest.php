<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'size' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
