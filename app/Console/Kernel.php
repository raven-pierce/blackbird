<?php

namespace App\Console;

use App\Jobs\RunReport;
use App\Jobs\SyncDirectoryUsers;
use App\Jobs\SyncLectureAttendance;
use App\Jobs\SyncLectureRecordings;
use App\Models\Enrollment;
use App\Models\Lecture;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            SyncDirectoryUsers::dispatch();
        })->daily();

        $schedule->call(function () {
            $lectures = Lecture::query()->whereDate('start_date', today()->subDay())->get();
            $lectures->each(fn (Lecture $lecture) => SyncLectureRecordings::dispatch($lecture));
        })->dailyAt('03:00');

        $schedule->call(fn () => SyncLectureAttendance::dispatch())->dailyAt('06:00');

        $schedule->call(function () {
            $enrollments = Enrollment::all();
            $enrollments->each(fn (Enrollment $enrollment) => RunReport::dispatch($enrollment, today()->subWeek(), today()));
        })->weekly();

        $schedule->call(function () {
            $enrollments = Enrollment::all();
            $enrollments->each(fn (Enrollment $enrollment) => $enrollment->generateInvoice());
        })->monthlyOn(time: '12:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
