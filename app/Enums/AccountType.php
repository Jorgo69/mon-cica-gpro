<?php

namespace App\Enums;

/**
 * Type de compte visible directement dans la table users.
 * Reflète le niveau le plus élevé du user — lisible sans aller fouiller ailleurs.
 *
 * Le rôle par organisation (admin dans org A, membre dans org B) reste dans la pivot organization_user.
 */
enum AccountType: string
{
    case SYSTEM_ADMIN = 'system_admin'; // Super admin plateforme
    case ORG_ADMIN    = 'org_admin';    // Créateur / Admin d'organisation
    case ORG_MEMBER   = 'org_member';   // Collaborateur dans une organisation
    case INDEPENDENT  = 'independent';  // Espace personnel sans organisation

    public function label(): string
    {
        return match($this) {
            self::SYSTEM_ADMIN => 'Administrateur Système',
            self::ORG_ADMIN    => 'Administrateur Organisation',
            self::ORG_MEMBER   => 'Collaborateur',
            self::INDEPENDENT  => 'Indépendant',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::SYSTEM_ADMIN => 'rose',
            self::ORG_ADMIN    => 'indigo',
            self::ORG_MEMBER   => 'emerald',
            self::INDEPENDENT  => 'amber',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::SYSTEM_ADMIN => 'shield-alert',
            self::ORG_ADMIN    => 'shield-check',
            self::ORG_MEMBER   => 'users',
            self::INDEPENDENT  => 'user',
        };
    }
}
