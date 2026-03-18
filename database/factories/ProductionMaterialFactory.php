<?php

namespace Database\Factories;

use App\Models\;
use App\Models\ProductionBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionMaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'production_batch_id' => ProductionBatch::factory(),
            'material_id' => ::factory(),
            'quantity_used' => fake()->randomFloat(2, 0, 9999999999999.99),
            'rate_at_time' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total_cost' => fake()->randomFloat(2, 0, 9999999999999.99),
        ];
    }
}
