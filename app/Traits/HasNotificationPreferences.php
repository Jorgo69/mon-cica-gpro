<?php

namespace App\Traits;

use App\Enums\NotificationType;

trait HasNotificationPreferences
{
    public function getNotificationChannels(NotificationType $type): array
    {
        // Global email toggle
        $emailEnabled = (bool) $this->getMeta('notifications.email', true);

        // Per-type preferences (stored as array of channels)
        $channels = $this->getMeta("notifications.preferences.{$type->value}");

        // If no preference set, use defaults
        if ($channels === null) {
            $channels = $type->defaultChannels();
        }

        // If global email is off, remove 'mail' from channels
        if (!$emailEnabled) {
            $channels = array_values(array_diff($channels, ['mail']));
        }

        // If user has no FCM tokens, remove 'fcm' channel
        if (in_array('fcm', $channels) && $this->fcmTokens()->count() === 0) {
            $channels = array_values(array_diff($channels, ['fcm']));
        }

        // Map 'fcm' to the actual channel class
        return array_map(fn($ch) => $ch === 'fcm' ? \NotificationChannels\Fcm\FcmChannel::class : $ch, $channels);
    }

    public function setNotificationPreference(NotificationType $type, array $channels): void
    {
        $allowed = ['database', 'mail', 'fcm'];
        $channels = array_values(array_intersect($channels, $allowed));
        $this->setMeta("notifications.preferences.{$type->value}", $channels);
    }
}
