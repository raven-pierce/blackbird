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

class AttendancesImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     * @return Model|Attendance|null
     */
    public function model(array $row): Model|Attendance|null
    {
        $section = $this->getSection($row['azure_team_id']);
        $lecture = $this->getLecture($section, $row['join_time']);
        $enrollment = $this->getEnrollment($row['azure_email'], $section->id);

        $joinTime = Carbon::parse($row['join_time']);
        $leaveTime = Carbon::parse($row['leave_time']);

        $duration = $this->convertDurationToMinutes($row['duration']);

        if ($duration > 15 && $this->isAttendanceDuringLecture($section, $joinTime)) {
            return new Attendance([
                'enrollment_id' => $enrollment->id,
                'lecture_id' => $lecture->id,
                'join_time' => $joinTime,
                'leave_time' => $leaveTime,
                'invoice_id' => null,
                'duration' => $duration,
                'paid' => $row['paid'],
            ]);
        }
    }

    protected function isAttendanceDuringLecture(Section $section, Carbon $joinTime): bool
    {
        return $section->lectures()->whereDate('start_time', $joinTime)->get()->isNotEmpty();
    }

    protected function convertDurationToMinutes(int $seconds): float
    {
        return CarbonInterval::seconds($seconds)->totalMinutes;
    }

    protected function getSection(string $azure_team_id): Section
    {
        return Section::where('azure_team_id', $azure_team_id)->first();
    }

    protected function getLecture(Section $section, Carbon $joinTime): Lecture
    {
        return $section->lectures()->whereDate('start_time', $joinTime)->get()->firstOrFail();
    }

    protected function getEnrollment(string $azure_email, int $section_id): Enrollment
    {
        $user = User::whereRelation('profile', 'azure_email', $azure_email)->first();

        return Enrollment::where('user_id', $user->id)->where('section_id', $section_id)->first();
    }
}
