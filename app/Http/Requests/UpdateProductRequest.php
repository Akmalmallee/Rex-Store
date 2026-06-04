<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product') ?? $this->route('id');
        return [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:products,slug,' . $id,
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'stock' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'sizes' => 'nullable|array',
            'sizes.*.size' => 'required_with:sizes|string',
            'sizes.*.stock' => 'required_with:sizes|integer|min:0',
            'colors' => 'nullable|array',
            'colors.*.color' => 'required_with:colors|string|max:255',
            'colors.*.color_code' => 'nullable|string|max:255',
            'colors.*.stock' => 'required_with:colors|integer|min:0',
        ];
    }
}
