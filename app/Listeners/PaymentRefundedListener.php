<?php

namespace App\Listeners;

use App\Models\Enrollment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Paddle\Events\WebhookReceived;

class PaymentRefundedListener
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
        if ($event->payload['alert_name'] === 'subscription_payment_refunded') {
            $passthrough = json_decode($event->payload['passthrough'], true);

            $attendances = Enrollment::findOrFail($passthrough['enrollment_id'])->paidAttendances()->orderByDesc('updated_at')->take($event->payload['quantity'])->get();

            foreach ($attendances as $attendance) {
                $attendance->markAsUnpaid();
                $attendance->save();
            }
        }
    }
}
