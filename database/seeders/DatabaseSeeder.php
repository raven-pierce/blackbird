<?php

namespace Database\Seeders;

use App\Models\AwardingBody;
use App\Models\Course;
use App\Models\Material;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::factory(10)->create();
        Course::factory(100)->create();
        Material::factory(1000)->create();
    }
}
