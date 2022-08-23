<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\MicrosoftGraph;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncDirectoryUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected MicrosoftGraph $graph;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
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

        $directoryUsers = $this->graph->listUsers()->getValue();

        foreach ($directoryUsers as $user) {
            $user = User::updateOrCreate(
                ['email' => $user->getUserPrincipalName()],
                ['name' => $user->getDisplayName()]
            );

            $user->assignRole('user');

            SyncDirectoryEnrollments::dispatch($user);

            // TODO: Notification
        }
    }
}
