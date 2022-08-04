<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Course;
use App\Models\Pricing;
use App\Models\Profile;
use App\Models\Section;
use App\Models\Tag;
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

        User::factory(93)->has(Profile::factory())->create();

        Pricing::factory(10)->create();

        $tutors = User::factory(5)->has(Profile::factory())->create();

        foreach ($tutors as $tutor) {
            Course::factory(2)
                ->for($tutor, 'tutor')
                ->has(Section::factory(4, [
                    'pricing_id' => Pricing::all()->random(),
                ]))
                ->has(Tag::factory(5))
                ->create();
        }
    }
}
