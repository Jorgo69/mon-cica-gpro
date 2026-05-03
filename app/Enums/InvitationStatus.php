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
        return __('enums.invitation_status.' . match($this) {
            self::PENDING => 'pending',
            self::ACCEPTED => 'accepted',
            self::EXPIRED => 'expired',
            self::REVOKED => 'revoked',
        });
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
