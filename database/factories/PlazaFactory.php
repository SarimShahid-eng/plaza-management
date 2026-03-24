<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlazaFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'total_units' => fake()->numberBetween(-10000, 10000),
            'master_pool_balance' => fake()->randomFloat(2, 0, 9999999999999.99),
            'currency_code' => fake()->regexify('[A-Za-z0-9]{3}'),
        ];
    }
}
