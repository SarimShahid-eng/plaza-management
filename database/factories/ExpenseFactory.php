<?php

namespace Database\Factories;

use App\Models\ProductionBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'production_batch_id' => ProductionBatch::factory(),
            'expense_type' => fake()->word(),
            'amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'expense_date' => fake()->date(),
            'description' => fake()->text(),
        ];
    }
}
