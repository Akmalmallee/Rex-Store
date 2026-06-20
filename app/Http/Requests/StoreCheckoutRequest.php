<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'shipping_courier' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
