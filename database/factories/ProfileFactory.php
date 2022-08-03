<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'azure_email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'guardian_email' => fake()->unique()->safeEmail(),
            'guardian_phone' => fake()->unique()->phoneNumber(),
        ];
    }
}
