<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'updateId' => 'nullable|integer|exists:production_batches,id',
            'production_date'     => ['required', 'date'],
            'bricks_produced'     => ['required', 'numeric', 'min:1'],
            'bricks_wasted'       => ['required', 'numeric', 'min:0'],
            'waste_reason'        => ['nullable', 'string', 'max:500'],
            'labor_cost'          => ['required', 'numeric', 'min:0'],
            'fuel_cost'           => ['required', 'numeric', 'min:0'],
            'total_material_cost' => ['required', 'numeric', 'min:0'],
            'total_expense_cost'  => ['required', 'numeric', 'min:0'],
            'total_cost'          => ['required', 'numeric', 'min:0'],

            'materials'                      => ['required', 'array', 'min:1'],
            'materials.*.material_id'        => ['required', 'integer', 'exists:materials,id'],
            'materials.*.quantity_used'      => ['required', 'numeric', 'min:0.01'],
            'materials.*.rate_at_time'       => ['required', 'numeric', 'min:0'],
            'materials.*.total_cost'         => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'production_date.required'           => 'Production date is required.',
            'bricks_produced.required'           => 'Bricks produced is required.',
            'bricks_produced.min'                => 'Bricks produced must be at least 1.',
            'bricks_wasted.required'             => 'Bricks wasted is required.',
            'bricks_wasted.min'                  => 'Wasted bricks cannot be negative.',
            'labor_cost.required'                => 'Labor cost is required.',
            'fuel_cost.required'                 => 'Fuel cost is required.',
            'total_material_cost.required'       => 'Total material cost is required.',
            'total_expense_cost.required'        => 'Total expense cost is required.',
            'total_cost.required'                => 'Total cost is required.',
            'materials.required'                 => 'At least one material is required.',
            'materials.min'                      => 'At least one material is required.',
            'materials.*.material_id.required'   => 'Please select a material for each row.',
            'materials.*.material_id.exists'     => 'Selected material does not exist.',
            'materials.*.quantity_used.required' => 'Quantity is required for each material.',
            'materials.*.quantity_used.min'      => 'Quantity must be greater than zero.',
            'materials.*.rate_at_time.required'  => 'Rate is required for each material.',
            'materials.*.total_cost.required'    => 'Total cost is required for each material.',
        ];
    }
}
