<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Lecture;
use App\Models\Section;
use App\Models\User;
use App\Services\MicrosoftGraph;
use Carbon\Carbon;
use function config;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Microsoft\Graph\Generated\Models\Event;
use Microsoft\Graph\Generated\Models\MeetingAttendanceReport;
use Microsoft\Graph\Generated\Models\OnlineMeeting;

class SyncLectureAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 900;

    protected MicrosoftGraph $graph;

    protected Section $section;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('attendance');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->graph = new MicrosoftGraph();

        $events = $this->getCalendarEvents();

        $meetings = $this->getOnlineMeetings($events);
        $meetings->each(function (OnlineMeeting $meeting) {
            $date = Carbon::parse($meeting->getStartDateTime())->setTimezone(config('app.timezone'));
            $this->section = Section::query()->whereHas('lectures', function (Builder $query) use ($date) {
                $query->whereBetween('start_date', [
                    $date->copy()->startOfHour()->subMinutes(45),
                    $date->copy()->startOfHour()->addMinutes(45),
                ]);
            })->first();

            $reports = $this->getAttendanceReports($meeting);

            $reports->each(fn (MeetingAttendanceReport $report) => $this->persistAttendanceRecord($report));
        });
    }

    /**
     * Fetch and iterate through the admin user's calendar
     * for events that are online meetings and not canceled.
     *
     * @return Collection<Event>
     */
    protected function getCalendarEvents(): Collection
    {
        $user = $this->graph->getUser(config('services.azure.admin_email'));

        $calendar = $this->graph->getUserCalendarEvents($user->getId());
        $teamsEvents = $calendar->getValue();

        $events = Collection::make();
        foreach ($teamsEvents as $event) {
            if ($event->getIsOnlineMeeting() && ! $event->getIsCancelled()) {
                $events->add($event);
            }
        }

        return $events;
    }

    /**
     * Iterate through meeting events and return a collection
     * of the meeting objects.
     *
     * @param  Collection<Event>  $events
     * @return Collection<OnlineMeeting>
     */
    protected function getOnlineMeetings(Collection $events): Collection
    {
        $user = $this->graph->getUser(config('services.azure.admin_email'));

        $meetings = Collection::make();
        foreach ($events as $event) {
            $meetingUrl = $event->getOnlineMeeting()->getJoinUrl();
            $meetings->add($this->graph->getOnlineMeeting($user->getId(), $meetingUrl)->getValue()[0]);
        }

        return $meetings;
    }

    /**
     * Fetch a collection of a recurring meeting's attendance
     * reports.
     *
     * @return Collection<MeetingAttendanceReport>
     */
    protected function getAttendanceReports(OnlineMeeting $meeting): Collection
    {
        $user = $this->graph->getUser(config('services.azure.admin_email'));

        $reports = Collection::make();
        $teamsReports = $this->graph->listMeetingAttendanceReports($user->getId(), $meeting->getId());

        foreach ($teamsReports->getValue() as $report) {
            $report = $this->graph->getMeetingAttendanceReport($user->getId(), $meeting->getId(), $report->getId());
            $reports->add($report);
        }

        return $reports;
    }

    /**
     * Iterate through an attendance report's attendees
     * and an attendance record for each one of them.
     *
     * @return Collection<Attendance>
     */
    protected function persistAttendanceRecord(MeetingAttendanceReport $report): void
    {
        $date = Carbon::parse($report->getMeetingStartDateTime())->setTimezone(config('app.timezone'));
        $lecture = Lecture::query()
            ->whereBelongsTo($this->section)
            ->whereDate('start_date', $date)
            ->first();

        $attendees = $report->getAttendanceRecords();

        foreach ($attendees as $attendee) {
            if (! $lecture) {
                continue;
            }

            $student = User::query()->where('email', $attendee->getEmailAddress())->first();
            if (! $student) {
                continue;
            }

            $enrollment = Enrollment::query()->whereBelongsTo($student, 'student')->whereBelongsTo($lecture->section)->first();
            if (! $enrollment) {
                continue;
            }

            $leaveDate = $lecture->start_date->copy()->addSeconds($attendee->getTotalAttendanceInSeconds());
            $lecture->attendances()->updateOrCreate(['enrollment_id' => $enrollment->id], [
                'join_date' => $lecture->start_date,
                'leave_date' => $leaveDate,
                'duration' => $lecture->start_date->diffInMinutes($leaveDate),
            ]);
        }
    }
}
