<?php

namespace App\Enums;

enum AccountType: string
{
    case SYSTEM_ADMIN = 'system_admin'; // Root / Super Admin
    case ORG_ADMIN = 'org_admin';       // Propriétaire d'espace
    case ORG_USER = 'org_user';         // Collaborateur (Manager, Membre, etc.)
    case INDEPENDENT = 'independent';     // Utilisateur seul

    public function label(): string
    {
        return match($this) {
            self::SYSTEM_ADMIN => 'Administrateur Système',
            self::ORG_ADMIN => 'Administrateur Espace',
            self::ORG_USER => 'Collaborateur',
            self::INDEPENDENT => 'Indépendant',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::SYSTEM_ADMIN => 'rose',
            self::ORG_ADMIN => 'indigo',
            self::ORG_USER => 'emerald',
            self::INDEPENDENT => 'amber',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::SYSTEM_ADMIN => 'shield-alert',
            self::ORG_ADMIN => 'shield-check',
            self::ORG_USER => 'users',
            self::INDEPENDENT => 'user',
        };
    }
}
