<?php

namespace App\Filament\Widgets;

use App\Models\Lecture;
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

        if (auth()->user()->hasRole('icarus')) {
            $lectures->add(Lecture::all());
        }

        if (auth()->user()->hasRole('tutor')) {
            $lectures->add(Lecture::query()->taughtBy(auth()->user())->get());
        }

        foreach (auth()->user()->enrollments as $enrollment) {
            $lectures->add($enrollment->section->lecturesBetween($enrollment->section->start_date, $enrollment->section->end_date)->orderBy('start_date')->get());
        }

        $events = [];

        foreach ($lectures->flatten()->unique('id') as $lecture) {
            $events[] = [
                'id' => $lecture->id,
                'title' => $lecture->section->course->name,
                'start' => $lecture->start_date,
                'end' => $lecture->end_date,
            ];
        }

        return $events;
    }
}
