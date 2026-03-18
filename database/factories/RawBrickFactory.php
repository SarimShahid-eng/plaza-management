<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RawBrickFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'production_date' => fake()->date(),
            'quantity_produced' => fake()->numberBetween(-10000, 10000),
        ];
    }
}
