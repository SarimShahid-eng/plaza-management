<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'updateId' => 'nullable|integer|exists:materials,id',
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'stock_quantity' => ['required', 'numeric', 'min:0'],
            'avg_rate' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Material name is required.',
            'unit.required' => 'Unit is required.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.numeric' => 'Stock quantity must be a number.',
            'stock_quantity.min' => 'Stock quantity cannot be negative.',
            'avg_rate.required' => 'Average rate is required.',
            'avg_rate.numeric' => 'Average rate must be a number.',
            'avg_rate.min' => 'Average rate cannot be negative.',
        ];
    }
}
