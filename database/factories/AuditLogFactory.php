<?php

namespace Database\Factories;

use App\Models\Plaza;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'plaza_id' => Plaza::factory(),
            'user_id' => User::factory(),
            'action' => fake()->word(),
            'resource_type' => fake()->word(),
            'resource_id' => Resource::factory(),
            'before_state' => '{}',
            'after_state' => '{}',
        ];
    }
}
