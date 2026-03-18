<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'purchase_id' => ::factory(),
            'amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'payment_date' => fake()->date(),
            'note' => fake()->text(),
        ];
    }
}
