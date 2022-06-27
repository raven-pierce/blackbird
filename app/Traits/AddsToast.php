<?php

namespace App\Traits;

trait AddsToast
{
    public function addToast(string $message, string $level = 'success', int $duration = 5)
    {
        $toast = [
            'message' => $message,
            'level' => $level,
            'duration' => $duration,
        ];

        session()->flash('toast', $toast);
    }
}
