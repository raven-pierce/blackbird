<?php

namespace Database\Factories;

use App\Models\AwardingBody;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $awarding_bodies = AwardingBody::pluck('id')->toArray();

        return [
            'slug' => $this->faker->unique()->slug(3, false),
            'name' => $this->faker->words(3, true),
            'awarding_body_id' => $this->faker->randomElement($awarding_bodies),
        ];
    }
}
