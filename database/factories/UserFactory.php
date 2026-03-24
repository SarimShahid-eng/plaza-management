<?php

namespace Database\Factories;

use App\Models\Plaza;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'email' => fake()->safeEmail(),
            'password' => fake()->password(),
            'full_name' => fake()->word(),
            'phone_number' => fake()->phoneNumber(),
            'role' => fake()->randomElement(["admin","chairman","assistant","member"]),
            'plaza_id' => Plaza::factory(),
            'unit_id' => Unit::factory(),
        ];
    }
}
