<?php

namespace App\Listeners;

use App\Models\Enrollment;
use Laravel\Paddle\Events\WebhookReceived;

class PaymentSuccessfulListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        // TODO: Change webhook name in production
        if ($event->payload['alert_name'] === 'subscription_payment_succeeded') {
            $passthrough = json_decode($event->payload['passthrough'], true);

            $attendances = Enrollment::findOrFail($passthrough['enrollment_id'])->unpaidAttendances()->orderBy('created_at')->take($event->payload['quantity'])->get();

            foreach ($attendances as $attendance) {
                $attendance->markAsPaid();
                $attendance->save();
            }
        }
    }
}
