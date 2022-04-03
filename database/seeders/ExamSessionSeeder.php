<?php

namespace Database\Seeders;

use App\Models\AwardingBody;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExamSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('exam_sessions')->insert([
            'slug' => 'caie-fall-22',
            'term' => 2,
            'session' => Carbon::create(2022, 9, 1),
            'awarding_body_id' => AwardingBody::where('slug', 'caie')->first()->id,
        ]);

        DB::table('exam_sessions')->insert([
            'slug' => 'caie-winter-23',
            'term' => 0,
            'session' => Carbon::create(2023, 1, 1),
            'awarding_body_id' => AwardingBody::where('slug', 'caie')->first()->id,
        ]);

        DB::table('exam_sessions')->insert([
            'slug' => 'caie-summer-23',
            'term' => 1,
            'session' => Carbon::create(2023, 6, 1),
            'awarding_body_id' => AwardingBody::where('slug', 'caie')->first()->id,
        ]);

        DB::table('exam_sessions')->insert([
            'slug' => 'pearson-fall-22',
            'term' => 2,
            'session' => Carbon::create(2022, 9, 1),
            'awarding_body_id' => AwardingBody::where('slug', 'pearson')->first()->id,
        ]);

        DB::table('exam_sessions')->insert([
            'slug' => 'pearson-winter-23',
            'term' => 0,
            'session' => Carbon::create(2023, 1, 1),
            'awarding_body_id' => AwardingBody::where('slug', 'pearson')->first()->id,
        ]);

        DB::table('exam_sessions')->insert([
            'slug' => 'pearson-summer-23',
            'term' => 1,
            'session' => Carbon::create(2023, 6, 1),
            'awarding_body_id' => AwardingBody::where('slug', 'pearson')->first()->id,
        ]);

        DB::table('exam_sessions')->insert([
            'slug' => 'aqa-fall-22',
            'term' => 2,
            'session' => Carbon::create(2022, 9, 1),
            'awarding_body_id' => AwardingBody::where('slug', 'aqa')->first()->id,
        ]);

        DB::table('exam_sessions')->insert([
            'slug' => 'aqa-winter-23',
            'term' => 0,
            'session' => Carbon::create(2023, 1, 1),
            'awarding_body_id' => AwardingBody::where('slug', 'aqa')->first()->id,
        ]);

        DB::table('exam_sessions')->insert([
            'slug' => 'aqa-summer-23',
            'term' => 1,
            'session' => Carbon::create(2023, 6, 1),
            'awarding_body_id' => AwardingBody::where('slug', 'aqa')->first()->id,
        ]);
    }
}
