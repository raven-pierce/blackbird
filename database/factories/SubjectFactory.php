<?php

namespace Database\Factories;

use App\Models\AwardingBody;
use App\Models\Level;
use App\Models\ExamSession;
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
        return [
            'slug' => fake()->unique()->slug(3, false),
            'name' => fake()->words(3, true),
            'awarding_body_id' => fake()->randomElement(AwardingBody::pluck('id')->toArray()),
            'exam_session_id' => fake()->randomElement(ExamSession::pluck('id')->toArray()),
            'level_id' => fake()->randomElement(Level::pluck('id')->toArray()),
        ];
    }
}
