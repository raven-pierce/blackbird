<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\User;
use Carbon\CarbonInterval;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendancesImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $section = $this->getSection($row['azure_team_id']);

        $enrollment = $this->getEnrollment($row['azure_email'], $section->id);

        $duration = $this->convertDurationToMinutes($row['duration']);

        if ($duration > 15) {
            return new Attendance([
                'enrollment_id' => $enrollment->id,
                'section_id' => $section->id,
                'join_time' => $row['join_time'],
                'leave_time' => $row['leave_time'],
                'duration' => $duration,
                'paid' => $row['paid'],
            ]);
        }
    }

    protected function convertDurationToMinutes(int $seconds)
    {
        return CarbonInterval::seconds($seconds)->totalMinutes;
    }

    protected function getSection(string $azure_team_id)
    {
        return Section::where('azure_team_id', $azure_team_id)->first();
    }

    protected function getEnrollment(string $azure_email, int $section_id)
    {
        $user = User::whereRelation('profile', 'azure_email', $azure_email)->first();

        return Enrollment::where('user_id', $user->id)->where('section_id', $section_id)->first();
    }
}
