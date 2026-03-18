<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'updateId'             => ['nullable', 'integer', 'exists:sales,id'],
            'customer_id'          => ['required', 'integer', 'exists:customers,id'],
            'production_batch_id'  => ['required', 'integer', 'exists:production_batches,id'],
            'quantity_sold'        => ['required', 'numeric', 'min:1'],
            'rate_per_brick'       => ['required', 'numeric', 'min:0'],
            'total'                => ['required', 'numeric', 'min:0'],
            'sale_date'            => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'         => 'Please select a customer.',
            'customer_id.exists'           => 'Selected customer does not exist.',
            'production_batch_id.required' => 'Please select a production batch.',
            'production_batch_id.exists'   => 'Selected batch does not exist.',
            'quantity_sold.required'       => 'Quantity sold is required.',
            'quantity_sold.min'            => 'Quantity must be at least 1.',
            'rate_per_brick.required'      => 'Rate per brick is required.',
            'rate_per_brick.min'           => 'Rate cannot be negative.',
            'total.required'               => 'Total is required.',
            'sale_date.required'           => 'Sale date is required.',
        ];
    }
}
