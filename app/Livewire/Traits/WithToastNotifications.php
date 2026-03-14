<?php

namespace App\Livewire\Traits;

trait WithToastNotifications
{
    /**
     * Dispatch an instant AlpineJS Toast Notification without reloading or writing to session.
     */
    public function notifyToast(string $type, string $message, string $title = null, int $duration = 5000)
    {
        $this->dispatch('toast-notification', data: [
            'type' => $type,
            'message' => $message,
            'title' => $title,
            'duration' => $duration
        ]);
    }
}
