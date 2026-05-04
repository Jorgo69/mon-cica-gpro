<?php

namespace App\Livewire\V1\Notifications;

use Livewire\Attributes\On;
use Livewire\Component;

class NotificationCenterLivewire extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function getListeners()
    {
        $userId = auth()->id();

        if (!$userId || !isBroadcastingEnabled()) {
            return [];
        }

        return [
            "echo-private:user.{$userId},.NewNotification" => 'refreshNotifications',
        ];
    }

    public function refreshNotifications(): void
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
        return view('livewire.notifications.notification-center');
    }
}
