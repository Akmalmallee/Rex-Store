<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBodyProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:300',
            'body_type' => 'nullable|in:slim,average,athletic,plus,hourglass,pear,apple,rectangle',
            'preferred_size' => 'nullable|string|max:10',
            'shoulder_width' => 'nullable|numeric|min:20|max:80',
            'chest_circumference' => 'nullable|numeric|min:50|max:200',
            'waist_circumference' => 'nullable|numeric|min:40|max:200',
            'hip_circumference' => 'nullable|numeric|min:50|max:200',
            'inseam' => 'nullable|numeric|min:30|max:120',
        ];
    }
}
