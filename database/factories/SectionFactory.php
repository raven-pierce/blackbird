<?php

namespace Database\Factories;

use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Section::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => fake()->countryCode(),
            'start_day' => fake()->dateTimeThisYear(),
            'end_day' => fake()->dateTimeThisYear(),
            'delivery_method' => fake()->colorName(),
            'seats' => fake()->numberBetween(20, 100),
            'azure_team_id' => fake()->uuid(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Section $section) {
            $section->generateLectures(
                day: fake()->numberBetween(0, 6),
                startHour: fake()->numberBetween(0, 23),
                startMinute: fake()->numberBetween(0, 59),
                endHour: fake()->numberBetween(0, 23),
                endMinute: fake()->numberBetween(0, 59)
            );
        });
    }
}
