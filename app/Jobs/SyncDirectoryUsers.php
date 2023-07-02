<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\MicrosoftGraph;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SyncDirectoryUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected MicrosoftGraph $graph;

    protected Client $twilio;

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
     * @throws ConfigurationException
     */
    public function handle(): void
    {
        $this->graph = new MicrosoftGraph();
        $this->twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));

        $directoryUsers = $this->graph->listUsers()->getValue();

        $emails = Arr::map($directoryUsers, function ($value, $key) {
            return $value->getUserPrincipalName();
        });

        User::query()->whereNotIn('email', $emails)->forceDelete();

        foreach ($directoryUsers as $user) {
            try {
                $user = User::updateOrCreate(
                    ['email' => $user->getUserPrincipalName()],
                    [
                        'name' => $user->getDisplayName(),
                        'phone' => $user->getMobilePhone()
                            ? $this->twilio->lookups->v2->phoneNumbers($user->getMobilePhone())->fetch()->phoneNumber
                            : null,
                    ]
                );
            } catch (TwilioException $e) {
                Log::error($e->getMessage());
            }

            $user->assignRole('user');

            SyncDirectoryEnrollments::dispatch($user);

            // TODO: Notification
        }
    }
}
