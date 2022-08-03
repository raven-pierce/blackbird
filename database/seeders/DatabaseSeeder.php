<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AwardingBody;
use App\Models\Course;
use App\Models\ExamSession;
use App\Models\Level;
use App\Models\Pricing;
use App\Models\Profile;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory([
            'name' => 'Icarus',
            'email' => 'icarus@blackbird.io',
        ])->has(Profile::factory([
            'azure_email' => 'icarus@wingfall.io',
        ]))->create();

        User::factory([
            'name' => 'Athena',
            'email' => 'athena@blackbird.io',
        ])->has(Profile::factory([
            'azure_email' => 'athena@wingfall.io',
        ]))->create();

        User::factory(88)->has(Profile::factory())->create();

        Pricing::factory(10)->create();

        AwardingBody::factory(3)
            ->has(ExamSession::factory(5)
                ->has(Level::factory(3)
                    ->has(Subject::factory(10))))
            ->create();

        $tutors = User::factory(5)->has(Profile::factory())->create();

        foreach ($tutors as $tutor) {
            Course::factory(2, [
                'subject_id' => fake()->unique()->numberBetween(1, 450),
            ])
                ->for($tutor, 'tutor')
                ->has(Section::factory(4, [
                    'pricing_id' => Pricing::all()->random(),
                ]))
                ->create();
        }
    }
}
