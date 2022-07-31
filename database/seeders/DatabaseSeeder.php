<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call([
            PermissionSeeder::class,
            AwardingBodySeeder::class,
            ExamSessionSeeder::class,
            LevelSeeder::class,
        ]);

        Subject::factory(50)->create();

        $icarus = User::factory([
            'name' => 'Icarus',
            'email' => 'icarus@blackbird.io',
        ])->withPersonalTeam()->create();

        $icarus->assignRole('Icarus');

        $tutors = User::factory(5)->withPersonalTeam()->create();

        foreach ($tutors as $tutor) {
            $tutor->assignRole('Tutor');
        }

        $assistants = User::factory(15)->withPersonalTeam()->create();

        foreach ($assistants as $assistant) {
            $assistant->assignRole('Assistant');
        }

        $students = User::factory(250)->withPersonalTeam()->create();

        foreach ($students as $student) {
            $student->assignRole('Student');
        }
    }
}
