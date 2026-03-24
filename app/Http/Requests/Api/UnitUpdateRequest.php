<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UnitUpdateRequest extends FormRequest
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
            'unit_number' => ['required', 'string'],
            'floor' => ['nullable', 'integer'],
            'status' => ['required', 'in:Paid,Pending,Vacant'],
            'unit_type' => ['required', 'in:1bhk,2bhk,3bhk,studio,penthouse,other'],
            'resident_name' => ['nullable', 'string'],
            'resident_phone' => ['nullable', 'string'],
            'due' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'monthly_dues_amount' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'last_payment_date' => ['nullable'],
        ];
    }
}
