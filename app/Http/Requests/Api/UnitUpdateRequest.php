<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'status' => ['sometimes', Rule::in(['Occupied', 'Vacant'])],
            'floor' => ['nullable', 'integer'],
            'user_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
            'unit_id' => ['required', 'integer', 'exists:units,id'],

        ];
    }
}
