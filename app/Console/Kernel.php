<?php

namespace App\Console;

use App\Jobs\SyncDirectoryUsers;
use App\Jobs\SyncLectureRecordings;
use App\Jobs\SyncRecordingMetadata;
use App\Models\Lecture;
use App\Models\Recording;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            SyncDirectoryUsers::dispatch();
        })->daily();

        $schedule->call(function () {
            $lectures = Lecture::whereDate('start_time', today()->subDay())->get();

            foreach ($lectures as $lecture) {
                SyncLectureRecordings::dispatch($lecture);
            }
        })->dailyAt('03:00');

        $schedule->call(function () {
            $recordings = Recording::all();

            foreach ($recordings as $recording) {
                SyncRecordingMetadata::dispatch($recording);
            }
        })->dailyAt('03:00');
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
