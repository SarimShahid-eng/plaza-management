<?php

namespace Database\Factories;

use App\Models\Plaza;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlazaSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'plaza_id' => Plaza::factory(),
            'maintenance_approval_threshold' => fake()->randomFloat(2, 0, 9999999999999.99),
            'color'=>'blue',
            'monthly_dues_amount' => 5000,
            'late_fee_percentage' => fake()->randomFloat(2, 0, 999.99),
        ];
    }
}
