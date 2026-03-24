<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token' => fake()->word(),
            'platform' => fake()->randomElement(["ios","android","web"]),
            'device_name' => fake()->word(),
            'is_active' => fake()->boolean(),
        ];
    }
}
