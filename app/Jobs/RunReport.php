<?php

namespace App\Jobs;

use App\Models\Enrollment;
use App\Notifications\ReportGenerated;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Enrollment $enrollment, protected Carbon $start_date, protected Carbon $end_date)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $report = $this->enrollment->reports()->create([
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->enrollment->student->notify(new ReportGenerated($report));
    }
}
