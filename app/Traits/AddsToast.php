<?php

namespace App\Traits;

trait AddsToast
{
    public function addToast(string $type = 'success', string $message, bool $manuallyClose = false)
    {
        $toast = [
            'type' => $type,
            'message' => $message,
            'manuallyClose' => $manuallyClose
        ];

        session()->flash('toast', $toast);
    }
}
