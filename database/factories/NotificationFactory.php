<?php

namespace Database\Factories;

use App\Models\RelatedResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(["dues","ticket","announcement","payment","assessment","maintenance","reminder"]),
            'title' => fake()->sentence(4),
            'message' => fake()->text(),
            'is_read' => fake()->boolean(),
            'related_resource_id' => RelatedResource::factory(),
        ];
    }
}
