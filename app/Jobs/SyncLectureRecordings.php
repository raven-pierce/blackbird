<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\Lecture;
use App\Models\Recording;
use App\Models\Section;
use App\Services\MicrosoftGraph;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Microsoft\Graph\Generated\Models\Drive;
use Microsoft\Graph\Generated\Models\DriveItem;
use Microsoft\Graph\Generated\Models\Group;

class SyncLectureRecordings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Section $section;

    protected Course $course;

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
        $this->section = $this->lecture->section;
        $this->course = $this->section->course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->graph = new MicrosoftGraph();
        $this->group = $this->graph->getGroup($this->section->azure_team_id);
        $this->drive = $this->graph->getGroupDrive($this->group->getId());

        $recipients = $this->preparePermissionRecipients();
        $recordings = $this->getRecordings();
        $roles = ['read'];

        $recordings->each(function (DriveItem $recording) use ($recipients, $roles) {
            $file = $this->fetchRecording($recording);

            $this->lecture->recordings()->updateOrCreate(['azure_item_id' => $recording->getId()], [
                'file_name' => $file['file_name'],
                'file_path' => $file['file_path'],
                'file_url' => $file['file_url'],
            ]);

            if ($recipients) {
                $this->graph->addDriveItemPermissions($this->drive->getId(), $recording->getId(), $recipients, $roles);
            }

            $this->getInPersonLectures()->each(function (Lecture $lecture) use ($recording, $file) {
                $lecture->recordings()->updateOrCreate(['azure_item_id' => $recording->getId()], [
                    'file_name' => $file['file_name'],
                    'file_path' => $file['file_path'],
                    'file_url' => $file['file_url'],
                ]);
            });

            // TODO: Notification
        });
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
     * @return Collection
     */
    protected function getRecordings(): Collection
    {
        $recordingFolder = $this->graph->getGroupRecordingsFolder($this->group->getId(), $this->section->channel_folder ?? 'General', $this->section->recordings_folder ?? 'Recordings');
        $recordings = $this->graph->getDriveFolderItems(
            $this->group->getId(),
            $recordingFolder->getId())->getValue();

        $filteredRecordings = new Collection();

        foreach ($recordings as $recording) {
            $recordingDateTime = Carbon::parse($recording->getCreatedDateTime());

            if ($recordingDateTime->isBetween($this->lecture->start_time->startOfDay(), $this->lecture->end_time->endOfDay())) {
                $filteredRecordings->add($recording);
            }
        }

        return $filteredRecordings;
    }

    /**
     * Fetch and store the recording from Microsoft's servers,
     * returning an array with a temporary link.
     *
     * @param  DriveItem  $recording
     * @return array
     */
    protected function fetchRecording(DriveItem $recording): array
    {
        $filePath = "{$this->course->id}/{$this->section->code}/{$recording->getName()}";

        Storage::disk('recordings')->put($filePath,
            file_get_contents($recording->getAdditionalData()['@microsoft.graph.downloadUrl']));

        $url = Storage::disk('recordings')->temporaryUrl($filePath, now()->addHours(24));

        return [
            'file_name' => $recording->getName(),
            'file_path' => $filePath,
            'file_url' => $url,
        ];
    }

    /**
     * Fetches all in-person lectures from equivalent
     * sections within a ±1 day range.
     *
     * @return Collection
     */
    protected function getInPersonLectures(): Collection
    {
        $sections = Section::query()
            ->whereBelongsTo($this->course)->whereDeliveryMethod('In Person')->get()->except($this->section->id);

        return $sections->map(function (Section $section) {
            return $section->getLecturesBetween(
                start: $this->lecture->start_time->copy()->subDay()->startOfDay(),
                end: $this->lecture->start_time->copy()->addDay()->endOfDay())->first();
        });
    }
}
