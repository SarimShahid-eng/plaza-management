<?php

namespace Database\Factories;

use App\Models\MaintenancePost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'maintenance_post_id' => MaintenancePost::factory(),
            'file_url' => fake()->word(),
            'file_type' => fake()->word(),
            'file_name' => fake()->word(),
            'file_size_bytes' => fake()->word(),
            'uploaded_by' => User::factory(),
        ];
    }
}
