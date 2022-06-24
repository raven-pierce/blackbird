<?php

namespace Database\Seeders;

use App\Models\AwardingBody;
use App\Models\ExamSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_levels')->insert([
            'slug' => 'caie-o-level',
            'name' => 'Ordinary Level',
            'awarding_body_id' => AwardingBody::where('slug', 'caie')->first()->id,
            'exam_session_id' => ExamSession::where('slug', 'caie-fall-22')->first()->id,
        ]);

        DB::table('course_levels')->insert([
            'slug' => 'caie-as-level',
            'name' => 'Advanced Subsidiary Level',
            'awarding_body_id' => AwardingBody::where('slug', 'caie')->first()->id,
            'exam_session_id' => ExamSession::where('slug', 'caie-fall-22')->first()->id,
        ]);

        DB::table('course_levels')->insert([
            'slug' => 'caie-a-level',
            'name' => 'Advanced Level',
            'awarding_body_id' => AwardingBody::where('slug', 'caie')->first()->id,
            'exam_session_id' => ExamSession::where('slug', 'caie-fall-22')->first()->id,
        ]);

        DB::table('course_levels')->insert([
            'slug' => 'pearson-o-level',
            'name' => 'Ordinary Level',
            'awarding_body_id' => AwardingBody::where('slug', 'pearson')->first()->id,
            'exam_session_id' => ExamSession::where('slug', 'pearson-fall-22')->first()->id,
        ]);

        DB::table('course_levels')->insert([
            'slug' => 'pearson-as-level',
            'name' => 'Advanced Subsidiary Level',
            'awarding_body_id' => AwardingBody::where('slug', 'pearson')->first()->id,
            'exam_session_id' => ExamSession::where('slug', 'pearson-fall-22')->first()->id,
        ]);

        DB::table('course_levels')->insert([
            'slug' => 'pearson-a-level',
            'name' => 'Advanced Level',
            'awarding_body_id' => AwardingBody::where('slug', 'pearson')->first()->id,
            'exam_session_id' => ExamSession::where('slug', 'pearson-fall-22')->first()->id,
        ]);

        DB::table('course_levels')->insert([
            'slug' => 'aqa-o-level',
            'name' => 'Ordinary Level',
            'awarding_body_id' => AwardingBody::where('slug', 'aqa')->first()->id,
            'exam_session_id' => ExamSession::where('slug', 'aqa-fall-22')->first()->id,
        ]);

        DB::table('course_levels')->insert([
            'slug' => 'aqa-as-level',
            'name' => 'Advanced Subsidiary Level',
            'awarding_body_id' => AwardingBody::where('slug', 'aqa')->first()->id,
            'exam_session_id' => ExamSession::where('slug', 'aqa-fall-22')->first()->id,
        ]);

        DB::table('course_levels')->insert([
            'slug' => 'aqa-a-level',
            'name' => 'Advanced Level',
            'awarding_body_id' => AwardingBody::where('slug', 'aqa')->first()->id,
            'exam_session_id' => ExamSession::where('slug', 'aqa-fall-22')->first()->id,
        ]);
    }
}
