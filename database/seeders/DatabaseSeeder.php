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
            AwardingBodySeeder::class,
            ExamSessionSeeder::class,
            LevelSeeder::class,
        ]);

        Subject::factory(50)->create();

        User::factory([
            'name' => 'Icarus',
            'email' => 'icarus@blackbird.io',
        ])->withPersonalTeam()->create();

        User::factory(250)->withPersonalTeam()->create();
    }
}
