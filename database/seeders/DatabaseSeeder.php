<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        User::factory([
            'name' => 'Icarus',
            'email' => 'icarus@blackbird.io',
        ])->create();

        User::factory([
            'name' => 'Athena',
            'email' => 'athena@blackbird.io',
        ])->create();

        User::factory(250)->create();
    }
}
