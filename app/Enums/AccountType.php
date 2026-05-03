<?php

namespace App\Enums;

enum AccountType: string
{
    case ROOT = 'system_admin';          // Super Administrateur global (sans org)
    case ORG_ADMIN = 'org_admin';        // Administrateur d'espace
    case ORG_USER = 'org_user';          // Collaborateur (Manager, Membre, etc.)
    case INDEPENDENT = 'independent';    // Utilisateur seul

    public function label(): string
    {
        return __('enums.account_type.' . match($this) {
            self::ROOT => 'root',
            self::ORG_ADMIN => 'org_admin',
            self::ORG_USER => 'org_user',
            self::INDEPENDENT => 'independent',
        });
    }

    public function color(): string
    {
        return match($this) {
            self::ROOT => 'rose',
            self::ORG_ADMIN => 'indigo',
            self::ORG_USER => 'emerald',
            self::INDEPENDENT => 'amber',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ROOT => 'shield-alert',
            self::ORG_ADMIN => 'shield-check',
            self::ORG_USER => 'users',
            self::INDEPENDENT => 'user',
        };
    }

    /**
     * Roles qu'un utilisateur de ce type peut assigner a d'autres.
     * ROOT peut tout assigner sauf ROOT (creation manuelle uniquement).
     * ORG_ADMIN peut assigner org_admin et org_user.
     * Les autres ne peuvent rien assigner.
     */
    public function assignableRoles(): array
    {
        return match($this) {
            self::ROOT => [self::ORG_ADMIN, self::ORG_USER, self::INDEPENDENT],
            self::ORG_ADMIN => [self::ORG_ADMIN, self::ORG_USER],
            default => [],
        };
    }
}
