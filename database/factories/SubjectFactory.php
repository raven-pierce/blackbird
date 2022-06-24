<?php

namespace Database\Factories;

use App\Models\AwardingBody;
use App\Models\CourseLevel;
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
            'slug' => $this->faker->unique()->slug(3, false),
            'name' => $this->faker->words(3, true),
            'awarding_body_id' => $this->faker->randomElement(AwardingBody::pluck('id')->toArray()),
            'exam_session_id' => $this->faker->randomElement(ExamSession::pluck('id')->toArray()),
            'course_level_id' => $this->faker->randomElement(CourseLevel::pluck('id')->toArray()),
        ];
    }
}
