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
            'phone' => '+1 (123) 456-7890',
            'guardian_email' => 'icarus@blackbird.io',
            'guardian_phone' => '+1 (123) 456-7890',
        ]))->create();

        User::factory([
            'name' => 'Athena',
            'email' => 'athena@blackbird.io',
        ])->has(Profile::factory([
            'azure_email' => 'athena@wingfall.io',
            'phone' => '+1 (123) 456-7890',
            'guardian_email' => 'athena@blackbird.io',
            'guardian_phone' => '+1 (123) 456-7890',
        ]))->create();

        User::factory([
            'name' => 'Artemis',
            'email' => 'artemis@blackbird.io',
        ])->has(Profile::factory([
            'azure_email' => 'artemis@wingfall.io',
            'phone' => '+1 (123) 456-7890',
            'guardian_email' => 'artemis@blackbird.io',
            'guardian_phone' => '+1 (123) 456-7890',
        ]))->create();
    }
}
