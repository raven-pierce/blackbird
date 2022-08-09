<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Collection;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public function getViewData(): array
    {
        return $this->getScheduledLectures();
    }

    protected function getScheduledLectures(): array
    {
        $lectures = new Collection();

        foreach (auth()->user()->enrollments as $enrollment) {
            $lectures->add($enrollment->section->getLecturesBetween($enrollment->section->start_day, $enrollment->section->end_day));
        }

        $events = [];

        foreach ($lectures->flatten() as $lecture) {
            $events[] = [
                'id' => $lecture->id,
                'title' => $lecture->section->course->name,
                'start' => $lecture->start_time,
                'end' => $lecture->end_time,
            ];
        }

        return $events;
    }
}
