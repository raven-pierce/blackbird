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
        User::factory([
            'name' => 'Icarus',
            'email' => 'icarus@blackbird.io',
        ])->create();

        User::factory([
            'name' => 'Athena',
            'email' => 'athena@blackbird.io',
        ])->create();

        User::factory([
            'name' => 'Artemis',
            'email' => 'artemis@blackbird.io',
        ])->create();
    }
}
