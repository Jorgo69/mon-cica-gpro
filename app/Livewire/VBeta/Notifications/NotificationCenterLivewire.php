<?php

namespace App\Livewire\VBeta\Notifications;

use Livewire\Component;

class NotificationCenterLivewire extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = auth()->user();
        if ($user) {
            $this->unreadCount = $user->unreadNotifications()->count();
            $this->notifications = $user->notifications()->take(5)->get();
        }
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.v-beta.notifications.notification-center-livewire');
    }
}
