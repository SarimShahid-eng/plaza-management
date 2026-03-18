<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'sale_id' => ::factory(),
            'amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'payment_date' => fake()->date(),
            'note' => fake()->text(),
        ];
    }
}
