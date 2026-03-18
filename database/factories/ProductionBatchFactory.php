<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'batch_no' => fake()->word(),
            'production_date' => fake()->date(),
            'raw_bricks_used' => fake()->numberBetween(-10000, 10000),
            'bricks_produced' => fake()->numberBetween(-10000, 10000),
            'labor_cost' => fake()->randomFloat(2, 0, 9999999999999.99),
            'fuel_cost' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total_material_cost' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total_expense_cost' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total_cost' => fake()->randomFloat(2, 0, 9999999999999.99),
        ];
    }
}
