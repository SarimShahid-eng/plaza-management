<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'unit' => fake()->word(),
            'stock_quantity' => fake()->randomFloat(2, 0, 9999999999999.99),
            'avg_rate' => fake()->randomFloat(2, 0, 9999999999999.99),
        ];
    }
}
