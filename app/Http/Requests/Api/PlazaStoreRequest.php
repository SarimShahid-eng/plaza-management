<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PlazaStoreRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
            'total_units' => ['required', 'integer'],
            'master_pool_balance' => ['required', 'numeric', 'between:-9999999999999.99,9999999999999.99'],
            'currency_code' => ['required', 'string', 'max:3'],
        ];
    }
}
