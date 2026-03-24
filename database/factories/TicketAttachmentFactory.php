<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'file_url' => fake()->word(),
            'file_type' => fake()->word(),
            'file_name' => fake()->word(),
            'file_size_bytes' => fake()->word(),
            'uploaded_by' => User::factory(),
        ];
    }
}
