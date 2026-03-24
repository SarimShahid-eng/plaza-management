<?php

namespace Database\Factories;

use App\Models\Plaza;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialAssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'plaza_id' => Plaza::factory(),
            'title' => fake()->sentence(4),
            'reason' => fake()->text(),
            'required_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'shortfall' => fake()->randomFloat(2, 0, 9999999999999.99),
            'occupied_units' => fake()->numberBetween(-10000, 10000),
            'per_unit_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'status' => fake()->randomElement(["PENDING","APPROVED","REJECTED","APPROVED_AND_IMPLEMENTED"]),
            'created_by' => User::factory(),
            'approved_by' => User::factory(),
        ];
    }
}
