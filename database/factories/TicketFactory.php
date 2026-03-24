<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'subject' => fake()->word(),
            'category' => fake()->randomElement(["Plumbing","Electrical","Cleaning","Noise","Security","Safety","Other"]),
            'description' => fake()->text(),
            'status' => fake()->randomElement(["Pending","InProgress","Resolved"]),
            'priority' => fake()->randomElement(["Low","Medium","High","Urgent"]),
            'assigned_to' => User::factory(),
            'created_by' => User::factory(),
            'resolved_at' => fake()->dateTime(),
            'resolution_notes' => fake()->text(),
        ];
    }
}
