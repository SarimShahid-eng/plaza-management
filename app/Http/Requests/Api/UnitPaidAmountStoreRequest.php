<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UnitPaidAmountStoreRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'integer'],
            'amount_type' => ['required', 'in:assessment,monthly,custom'],
            'assessment_id' => ['required_if:amount_type,assessment', 'nullable', 'exists:special_assessments,id'],
            // 2026-03
            'custom_month'=>['required_if:amount_type,custom_month']
        ];
    }

    public function messages(): array
    {
        return [
            'unit_id.required' => 'unit must be provided',
            'assessment_id.required_if' => 'assesment must be provided when type is assesment',
        ];
    }
}
