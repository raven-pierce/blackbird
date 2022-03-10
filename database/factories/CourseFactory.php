<?php

namespace Database\Factories;

use App\Models\AwardingBody;
use App\Models\CourseLevel;
use App\Models\ExamSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'subject' => $this->faker->word(),
            'awarding_body_id' => AwardingBody::inRandomOrder()->get()->first()->getKey(),
            'course_level_id' => CourseLevel::inRandomOrder()->get()->first()->getKey(),
            'exam_session_id' => ExamSession::inRandomOrder()->get()->first()->getKey(),
            'status' => $this->faker->numberBetween(1, 3),
            'tutor_id' => User::inRandomOrder()->get()->first()->getKey(),
            'thumbnail_url' => $this->faker->imageUrl(1080, 1920),
        ];
    }
}
