<?php

namespace App\Enums;

/**
 * Rôle d'un utilisateur au sein d'une organisation spécifique.
 * Stocké dans organization_user.role (string en DB).
 */
enum OrgMemberRole: string
{
    case ORG_ADMIN    = 'org_admin';    // Admin de l'organisation / siège
    case BRANCH_ADMIN = 'branch_admin'; // Admin d'une annexe
    case MEMBER       = 'member';       // Collaborateur

    public function label(): string
    {
        return match($this) {
            self::ORG_ADMIN    => 'Administrateur',
            self::BRANCH_ADMIN => 'Admin Annexe',
            self::MEMBER       => 'Collaborateur',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ORG_ADMIN    => 'indigo',
            self::BRANCH_ADMIN => 'violet',
            self::MEMBER       => 'emerald',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ORG_ADMIN    => 'shield-check',
            self::BRANCH_ADMIN => 'building-2',
            self::MEMBER       => 'user-check',
        };
    }
}
