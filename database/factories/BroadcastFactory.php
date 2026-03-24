<?php

namespace Database\Factories;

use App\Models\Plaza;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BroadcastFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'plaza_id' => Plaza::factory(),
            'title' => fake()->sentence(4),
            'message' => fake()->text(),
            'is_urgent' => fake()->boolean(),
            'sent_to_count' => fake()->numberBetween(-10000, 10000),
            'created_by' => User::factory(),
        ];
    }
}
