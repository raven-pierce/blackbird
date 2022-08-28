<?php

namespace App\Jobs;

use App\Models\Lecture;
use App\Models\Recording;
use App\Services\MicrosoftGraph;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Microsoft\Graph\Generated\Models\Drive;
use Microsoft\Graph\Generated\Models\Group;

class SyncLectureRecordings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected MicrosoftGraph $graph;

    protected Group $group;

    protected Drive $drive;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Lecture $lecture)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->graph = new MicrosoftGraph();
        $this->group = $this->graph->getGroup($this->lecture->section->azure_team_id);
        $this->drive = $this->graph->getGroupDrive($this->group->getId());

        $recipients = $this->preparePermissionRecipients();
        $recordings = $this->getRecordings();
        $roles = ['read'];

        foreach ($recordings as $recording) {
            Recording::updateOrCreate(['azure_item_id' => $recording->getId()], [
                'lecture_id' => $this->lecture->id,
                'file_url' => $recording->getWebUrl(),
            ]);

            if ($recipients) {
                $this->graph->addDriveItemPermissions($this->drive->getId(), $recording->getId(), $recipients, $roles);
            }
        }

        // TODO: Notification
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

    /**
     * Fetches all recordings belonging to a Section,
     * then filters them down to those created today.
     *
     * @return array|null
     */
    protected function getRecordings(): ?array
    {
        $recordingFolder = $this->graph->getGroupRecordingsFolder($this->group->getId(), $this->lecture->section->channel_folder ?? 'General', $this->lecture->section->recordings_folder ?? 'Recordings');
        $recordings = $this->graph->getDriveFolderItems(
            $this->group->getId(),
            $recordingFolder->getId())->getValue();

        $filteredRecordings = [];

        foreach ($recordings as $recording) {
            $recordingDateTime = Carbon::parse($recording->getCreatedDateTime());

            if ($recordingDateTime->isBetween($this->lecture->start_time->startOfDay(), $this->lecture->end_time->endOfDay())) {
                $filteredRecordings[] = $recording;
            }
        }

        return $filteredRecordings;
    }
}
