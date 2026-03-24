<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'unit_id' => ['required', 'integer', 'exists:units,id'],
            'amount' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'payment_type' => ['required', 'in:App,Cash,BankTransfer,Cheque,Card'],
            'payment_month' => ['required', 'string', 'max:7'],
        ];
    }
}
