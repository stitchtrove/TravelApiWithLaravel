<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    public function definition(): array
    {
        $no_of_days = rand(1, 10);
        return [
            'is_public' => fake()->boolean(),
            'name' => fake()->text(20),
            'description' => fake()->text(100),
            'no_of_days' => $no_of_days,
            'no_of_nights' => $no_of_days - 1
        ];
    }
}
