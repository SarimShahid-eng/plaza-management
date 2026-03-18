<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'production_batch_id' => ::factory(),
            'quantity_sold' => fake()->numberBetween(-10000, 10000),
            'rate_per_brick' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total' => fake()->randomFloat(2, 0, 9999999999999.99),
            'sale_date' => fake()->date(),
        ];
    }
}
