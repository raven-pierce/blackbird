<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AwardingBodySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('awarding_bodies')->insert([
            'slug' => 'caie',
            'name' => 'Cambridge Assessment International Examinations',
        ]);

        DB::table('awarding_bodies')->insert([
            'slug' => 'pearson',
            'name' => 'Pearson Edexcel',
        ]);

        DB::table('awarding_bodies')->insert([
            'slug' => 'aqa',
            'name' => 'Oxford AQA',
        ]);
    }
}
