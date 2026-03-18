<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'material_id' => ::factory(),
            'quantity' => fake()->randomFloat(2, 0, 9999999999999.99),
            'rate' => fake()->randomFloat(2, 0, 9999999999999.99),
            'total' => fake()->randomFloat(2, 0, 9999999999999.99),
            'purchase_date' => fake()->date(),
        ];
    }
}
