<?php

namespace App\Jobs;

use App\Models\Lecture;
use App\Models\Recording;
use App\Models\Section;
use App\Services\MicrosoftGraph;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Microsoft\Graph\Generated\Models\Drive;
use Microsoft\Graph\Generated\Models\Group;

class SyncRecordingPermissions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Lecture $lecture;

    protected Section $section;

    protected MicrosoftGraph $graph;

    protected Group $group;

    protected Drive $drive;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Recording $recording)
    {
        $this->queue = 'recordings';
        
        $this->lecture = $this->recording->lecture;
        $this->section = $this->lecture->section;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->graph = new MicrosoftGraph();
        $this->group = $this->graph->getGroup($this->section->azure_team_id);
        $this->drive = $this->graph->getGroupDrive($this->group->getId());

        $recording = $this->graph->getRecordingItem($this->section->azure_team_id, $this->recording->azure_item_id);

        $recipients = $this->preparePermissionRecipients();
        $roles = ['read'];

        if ($recipients) {
            $this->graph->addDriveItemPermissions($this->drive->getId(), $recording->getId(), $recipients, $roles);
        }
    }

    /**
     * Prepares an array of all attendees of the lecture.
     *
     * @return array
     */
    protected function preparePermissionRecipients(): array
    {
        $recipients = [];

        foreach ($this->lecture->attendances as $attendance) {
            $recipients[] = $attendance->enrollment->student->email;
        }

        return $recipients;
    }
}
