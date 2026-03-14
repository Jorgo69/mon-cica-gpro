<?php

namespace App\Helpers;

class Notifier
{
    public function success(string $message, string $title = 'Succès')
    {
        $this->flash('success', $message, $title);
        return $this;
    }

    public function error(string $message, string $title = 'Erreur')
    {
        $this->flash('error', $message, $title);
        return $this;
    }

    public function info(string $message, string $title = 'Information')
    {
        $this->flash('info', $message, $title);
        return $this;
    }

    public function warning(string $message, string $title = 'Attention')
    {
        $this->flash('warning', $message, $title);
        return $this;
    }

    protected function flash(string $type, string $message, string $title)
    {
        session()->flash('toast_notification', [
            'type' => $type,
            'message' => $message,
            'title' => $title,
            'duration' => 5000
        ]);
    }
}
