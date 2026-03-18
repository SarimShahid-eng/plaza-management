<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'updateId' => 'nullable|integer|exists:purchases,id',
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'material_id' => ['required', 'integer', 'exists:materials,id'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'rate' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'purchase_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.exists' => 'Selected supplier does not exist.',
            'updateId.exists' => 'Selected purchase does not exist.',
            'material_id.required' => 'Please select a material.',
            'material_id.exists' => 'Selected material does not exist.',
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity must be greater than zero.',
            'rate.required' => 'Rate is required.',
            'rate.numeric' => 'Rate must be a number.',
            'rate.min' => 'Rate cannot be negative.',
            'total.required' => 'Total is required.',
            'total.numeric' => 'Total must be a number.',
            'total.min' => 'Total cannot be negative.',
            'purchase_date.required' => 'Purchase date is required.',
            'purchase_date.date' => 'Purchase date must be a valid date.',
        ];
    }
}
