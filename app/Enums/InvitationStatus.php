<?php

namespace App\Enums;

enum InvitationStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case EXPIRED = 'expired';
    case REVOKED = 'revoked';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::ACCEPTED => 'Acceptée',
            self::EXPIRED => 'Expirée',
            self::REVOKED => 'Révoquée',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'amber',
            self::ACCEPTED => 'emerald',
            self::EXPIRED => 'gray',
            self::REVOKED => 'rose',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PENDING => 'clock',
            self::ACCEPTED => 'check-circle',
            self::EXPIRED => 'timer-off',
            self::REVOKED => 'x-circle',
        };
    }
}
