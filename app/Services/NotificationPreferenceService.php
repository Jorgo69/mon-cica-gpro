<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\User;

class NotificationPreferenceService
{
    public static function getAll(User $user): array
    {
        $preferences = [];

        foreach (NotificationType::userConfigurable() as $type) {
            $channels = $user->getMeta("notifications.preferences.{$type->value}");
            $preferences[$type->value] = $channels ?? $type->defaultChannels();
        }

        return $preferences;
    }

    public static function update(User $user, string $type, array $channels): void
    {
        $notifType = NotificationType::tryFrom($type);
        if (!$notifType) return;

        $user->setNotificationPreference($notifType, $channels);
    }
}
