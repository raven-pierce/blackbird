<?php

namespace App\Jobs;

use App\Models\Enrollment;
use App\Models\Section;
use App\Models\User;
use App\Services\MicrosoftGraph;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Microsoft\Graph\Generated\Models\Group;

class SyncDirectoryEnrollments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected MicrosoftGraph $graph;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected User $user)
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
        $sections = $this->parseUserMemberships($this->user);

        foreach ($sections as $section) {
            $this->syncEnrollments($section);
        }
    }

    /**
     * Parse the Microsoft AD user's memberships to their
     * respective sections.
     *
     * @param  User  $user
     * @return array
     */
    protected function parseUserMemberships(User $user): array
    {
        $memberships = $this->graph->listUserGroups($user->email)->getValue();

        $sections = [];

        foreach ($memberships as $membership) {
            $section = Section::where('azure_team_id', $membership->getId())->first();

            if ($membership instanceof Group && $section) {
                $sections[] = $section;
            }
        }

        return $sections;
    }

    /**
     * Sync the user's enrollments with their currently
     * assigned sections from Microsoft AD.
     *
     * @param  Section  $section
     * @return void
     */
    protected function syncEnrollments(Section $section): void
    {
        $enrollment = Enrollment::updateOrCreate([
            'section_id' => $section->id,
            'user_id' => $this->user->id,
        ]);

        // TODO: Notification
    }
}
