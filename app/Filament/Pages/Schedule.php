<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
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

    public string $greeting;

    public function mount()
    {
        $this->lectures = $this->scheduledLectures(auth()->user());

        $this->greeting = $this->greeting();
    }

    protected function scheduledLectures(User $user): Collection
    {
        $this->lectures = new Collection();

        foreach ($user->enrollments as $enrollment) {
            $this->lectures->add($enrollment->section->getLecturesBetween(now(), now()->addWeek()->endOfDay()));
        }

        return $this->lectures->flatten();
    }

    protected function greeting(): string
    {
        Carbon::macro('greet', function () {
            $hour = $this->format('H');

            if ($hour < 12) {
                return 'Good Morning';
            }

            if ($hour < 17) {
                return 'Good Afternoon';
            }

            return 'Good Evening';
        });

        return now()->greet();
    }
}
