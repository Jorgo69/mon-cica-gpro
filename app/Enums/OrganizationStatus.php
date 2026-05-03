<?php

namespace App\Enums;

enum OrganizationStatus: string
{
    case TRIAL     = 'trial';
    case ACTIVE    = 'active';
    case SUSPENDED = 'suspended';
    case CANCELLED = 'cancelled';
    case INACTIVE  = 'inactive';

    public function label(): string
    {
        return __('enums.organization_status.' . $this->value);
    }

    public function color(): string
    {
        return match($this) {
            self::TRIAL     => 'blue',
            self::ACTIVE    => 'success',
            self::SUSPENDED => 'warning',
            self::CANCELLED => 'error',
            self::INACTIVE  => 'slate',
        };
    }
}
