<?php

namespace App\Enums;

enum AccountType: string
{
    case ADMIN = 'admin';
    case SUPERVISOR = 'supervisor';
    case MANAGER = 'manager';
    case MEMBER = 'member';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrateur IT',
            self::SUPERVISOR => 'Superviseur',
            self::MANAGER => 'Responsable de Projet',
            self::MEMBER => 'Membre / Agent de terrain',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ADMIN => 'rose',
            self::SUPERVISOR => 'indigo',
            self::MANAGER => 'amber',
            self::MEMBER => 'emerald',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ADMIN => 'shield-check',
            self::SUPERVISOR => 'eye',
            self::MANAGER => 'briefcase',
            self::MEMBER => 'user',
        };
    }
}
