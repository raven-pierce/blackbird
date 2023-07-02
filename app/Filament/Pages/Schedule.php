<?php

namespace App\Filament\Pages;

use App\Models\Lecture;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class Schedule extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.schedule';

    protected static ?string $title = 'Schedule';

    protected static ?string $navigationLabel = 'Schedule';

    protected static ?string $slug = 'schedule';

    public Collection $lectures;

    public function mount()
    {
        $this->lectures = $this->scheduledLectures(auth()->user());
    }

    protected function scheduledLectures(User $user): Collection
    {
        if ($user->hasRole('icarus')) {
            return Lecture::query()->whereBetween('start_date', [now(), now()->endOfDay()])->orderBy('start_date')->get();
        }

        if ($user->hasRole('tutor')) {
            return Lecture::query()->whereBetween('start_date', [now(), now()->endOfDay()])->orderBy('start_date')->taughtBy($user)->get();
        }

        $this->lectures = new Collection();

        foreach ($user->enrollments as $enrollment) {
            $this->lectures->add($enrollment->section->lecturesBetween(now(), now()->endOfDay())->orderBy('start_date')->get());
        }

        return $this->lectures->flatten();
    }
}
