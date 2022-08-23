<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Lecture;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class Attendances implements ToModel, WithHeadingRow, WithUpserts, WithUpsertColumns
{
    /**
     * @param  array  $row
     * @return Model|Attendance|null
     */
    public function model(array $row): Model|Attendance|null
    {
        $joinTime = Carbon::parse($row['join_time']);
        $leaveTime = Carbon::parse($row['leave_time']);

        $section = $this->getSection($row['azure_team_id']);
        $lecture = $this->getLecture($section, $joinTime);
        $enrollment = $this->getEnrollment($row['email'], $section->id);

        $duration = $this->convertDurationToMinutes($row['duration']);

        if ($duration > 15 && $this->isAttendanceDuringLecture($section, $joinTime)) {
            return new Attendance([
                'enrollment_id' => $enrollment->id,
                'lecture_id' => $lecture->id,
                'join_time' => $joinTime,
                'leave_time' => $leaveTime,
                'invoice_id' => null,
                'duration' => $duration,
                'paid' => false,
            ]);
        }
    }

    protected function isAttendanceDuringLecture(Section $section, Carbon $joinTime): bool
    {
        return $section->lectures()->whereDate('start_time', $joinTime)->get()->isNotEmpty();
    }

    protected function convertDurationToMinutes(string $duration): float
    {
        return floor(CarbonInterval::fromString($duration)->totalMinutes);
    }

    protected function getSection(string $azure_team_id): Section
    {
        return Section::where('azure_team_id', $azure_team_id)->first();
    }

    protected function getLecture(Section $section, Carbon $joinTime): Lecture
    {
        return $section->lectures()->whereDate('start_time', $joinTime)->get()->first();
    }

    protected function getEnrollment(string $email, int $section_id): Enrollment
    {
        $user = User::where('email', $email)->first();

        return Enrollment::where('user_id', $user->id)->where('section_id', $section_id)->first();
    }

    public function upsertColumns(): array
    {
        return ['join_time', 'leave_time', 'duration'];
    }

    public function uniqueBy(): array
    {
        return ['enrollment_id', 'lecture_id'];
    }
}
