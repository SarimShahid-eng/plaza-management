<?php

namespace Database\Factories;

use App\Models\Plaza;
use App\Models\SpecialAssessment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenancePostFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'plaza_id' => Plaza::factory(),
            'title' => fake()->sentence(4),
            'category' => fake()->randomElement(["Plumbing","Electrical","Cleaning","Generator","Repair","Security","Painting","HVAC","Other"]),
            'cost' => fake()->randomFloat(2, 0, 9999999999999.99),
            'status' => fake()->randomElement(["IMPLEMENTED","PENDING_APPROVAL","APPROVED","REJECTED"]),
            'vendor_name' => fake()->word(),
            'vendor_phone' => fake()->word(),
            'created_by' => User::factory(),
            'approved_by' => User::factory(),
            'linked_ticket_id' => Ticket::factory(),
            'linked_assessment_id' => SpecialAssessment::factory(),
            'approval_notes' => fake()->text(),
        ];
    }
}
