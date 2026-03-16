<?php

namespace App\Enums;

enum OrganizationStatus: string
{
    case TRIAL = 'trial';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match($this) {
            self::TRIAL => 'Période d\'essai',
            self::ACTIVE => 'Actif',
            self::SUSPENDED => 'Suspendu',
            self::INACTIVE => 'Inactif',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::TRIAL => 'blue',
            self::ACTIVE => 'success',
            self::SUSPENDED => 'warning',
            self::INACTIVE => 'slate',
        };
    }
}
