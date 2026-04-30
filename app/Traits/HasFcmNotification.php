<?php

namespace App\Traits;

use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

trait HasFcmNotification
{
    public function toFcm(object $notifiable): FcmMessage
    {
        $data = $this->toArray($notifiable);

        return FcmMessage::create()
            ->notification(
                FcmNotification::create()
                    ->title($data['title'] ?? config('app.name'))
                    ->body($data['message'] ?? '')
            )
            ->data([
                'action_url' => $data['action_url'] ?? '/',
                'type' => $data['type'] ?? 'general',
            ]);
    }
}
