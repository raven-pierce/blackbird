<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use NumberFormatter;

class DashboardController extends Controller
{
    public function __invoke()
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

        $lectures = new Collection();

        foreach (auth()->user()->enrollments as $enrollment) {
            $lectures->add($enrollment->section->getLecturesBetween(now(), now()->endOfDay()));
        }

        return view('dashboard', [
            'lectures' => $lectures->flatten(),
            'greeting' => now()->greet(),
            'spellOutFormatter' => NumberFormatter::create('en-US', NumberFormatter::SPELLOUT),
        ]);
    }
}
