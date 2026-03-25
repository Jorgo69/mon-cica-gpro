<?php

namespace App\Enums;

enum OrganizationType: string
{
    case HEADQUARTERS = 'headquarters'; // Siège principal
    case BRANCH       = 'branch';       // Annexe / bureau secondaire

    public function label(): string
    {
        return match($this) {
            self::HEADQUARTERS => 'Siège',
            self::BRANCH       => 'Annexe',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::HEADQUARTERS => 'landmark',
            self::BRANCH       => 'building-2',
        };
    }
}
