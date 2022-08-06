<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Profile;
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

        User::factory(98)->has(Profile::factory())->create();
    }
}
