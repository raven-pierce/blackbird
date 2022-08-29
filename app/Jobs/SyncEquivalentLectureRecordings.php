<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\Lecture;
use App\Models\Recording;
use App\Models\Section;
use App\Services\MicrosoftGraph;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Microsoft\Graph\Generated\Models\Drive;
use Microsoft\Graph\Generated\Models\Group;

class SyncEquivalentLectureRecordings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Lecture $lecture;

    protected Section $section;

    protected Course $course;

    protected string $filePath;

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
        $this->lecture = $this->recording->lecture;
        $this->section = $this->lecture->section;
        $this->course = $this->section->course;

        $this->filePath = "{$this->course->id}/{$this->section->code}/{$this->recording->file_name}";
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

        $this->getEquivalentSections()->each(fn (Section $section) => $this->syncEquivalentRecording($section));
    }

    /**
     * Fetches all in-person only sections belonging
     * to the same course, except the provided one.
     *
     * @return Collection
     */
    protected function getEquivalentSections(): Collection
    {
        return Section::query()->whereBelongsTo($this->course)
            ->whereDeliveryMethod('In Person')->get()
            ->except($this->section->id);
    }

    /**
     * Finds all equivalent lectures that are ±1 day
     * from the provided lecture, and grabs the first one.
     *
     * @param  Section  $section
     * @return Lecture
     */
    protected function getEquivalentLecture(Section $section): Lecture
    {
        return $section->lectures()->whereBetween('start_time', [
            $this->lecture->start_time->copy()->subDay()->startOfDay(),
            $this->lecture->start_time->copy()->addDay()->endOfDay(),
        ])->first();
    }

    /**
     * Fetch and store the recording from Microsoft's servers,
     * and returns a temporary download link valid for 24 hours.
     *
     * @return string
     */
    protected function fetchRecording(): string
    {
        $graphRecording = $this->graph->getRecordingItem($this->section->azure_team_id, $this->recording->azure_item_id);

        $contents = file_get_contents($graphRecording->getAdditionalData()['@microsoft.graph.downloadUrl']);

        Storage::disk('recordings')->put($this->filePath, $contents);

        return Storage::disk('recordings')->temporaryUrl($this->filePath, now()->addHours(24));
    }

    /**
     * Creates a recording for the equivalent lecture using
     * the provided recording's original information, with a
     * path to the newly downloaded file.
     *
     * @param  Section  $section
     * @return void
     */
    protected function syncEquivalentRecording(Section $section): void
    {
        $lecture = $this->getEquivalentLecture($section);

        $lecture->recordings()->updateOrCreate(['azure_item_id' => $this->recording->azure_item_id], [
            'file_name' => $this->recording->file_name,
            'file_url' => $this->fetchRecording(),
        ]);
    }
}
