<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SpecialAssessmentStoreRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'reason' => ['required', 'string'],
            'required_amount' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            // 'shortfall' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            // 'occupied_units' => ['required', 'integer'],
            // 'per_unit_amount' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'created_by' => ['required', 'integer', 'exists:users,id'],
            // 'status' => ['required', 'in:PENDING,APPROVED,REJECTED,APPROVED_AND_IMPLEMENTED'],
            // 'approved_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
