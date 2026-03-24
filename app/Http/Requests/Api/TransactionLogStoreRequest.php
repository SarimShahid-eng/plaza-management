<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TransactionLogStoreRequest extends FormRequest
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
            'plaza_id' => ['required', 'integer', 'exists:plazas,id'],
            'transaction_type' => ['required', 'in:credit,debit'],
            'amount' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'description' => ['required', 'string'],
            'balance_before' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'balance_after' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'recorded_by' => ['required', 'integer', 'exists:users,id'],
            'related_resource_type' => ['required', 'in:maintenance_post,payment,special_assessment'],
            'related_resource_id' => ['required'],
        ];
    }
}
