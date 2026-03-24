<?php

namespace Database\Factories;

use App\Models\Plaza;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'plaza_id' => Plaza::factory(),
            'unit_id' => Unit::factory(),
            'amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'payment_type' => fake()->randomElement(["App","Cash","BankTransfer","Cheque","Card"]),
            'payment_month' => fake()->regexify('[A-Za-z0-9]{7}'),
            'status' => fake()->randomElement(["PENDING_APPROVAL","APPROVED","REJECTED"]),
            'is_late' => fake()->boolean(),
            'reference_number' => fake()->word(),
            'recorded_by' => User::factory(),
            'approved_by' => User::factory(),
        ];
    }
}
