<?php

namespace App\Notifications;

use App\Filament\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportGenerated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected Report $report)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your performance report has been generated!')
            ->line('We\'re reaching out to let you know that there\'s a new report in your dashboard!')
            ->line("This report is for the course {$this->report->enrollment->section->course->name}, taught by {$this->report->enrollment->section->course->tutor->name}, for the week of {$this->report->start_date->format('l, d F Y')}.")
            ->action('View Report', ReportResource::getUrl('view', $this->report))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
