<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'updateId'     => 'nullable|integer|exists:customer_payments,id',
            'customer_id'  => 'required|exists:customers,id',
            'amount'       => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'note'         => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'  => 'Please select a customer.',
            'customer_id.exists'    => 'Selected customer is invalid.',
            'amount.required'       => 'Payment amount is required.',
            'amount.numeric'        => 'Amount must be a number.',
            'payment_date.required' => 'Payment date is required.',
        ];
    }
}
