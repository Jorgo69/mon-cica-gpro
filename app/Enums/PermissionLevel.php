<?php

namespace App\Enums;

enum PermissionLevel: int
{
    case OBSERVER = 1;
    case CONTRIBUTOR = 2;
    case MANAGER = 3;
    case ADMIN = 4;

    public function label(): string
    {
        return __('enums.permission_level.' . $this->name);
    }

    public function description(): string
    {
        return __('enums.permission_level_desc.' . $this->name);
    }

    public function color(): string
    {
        return match ($this) {
            self::OBSERVER => 'slate',
            self::CONTRIBUTOR => 'blue',
            self::MANAGER => 'emerald',
            self::ADMIN => 'indigo',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::OBSERVER => 'eye',
            self::CONTRIBUTOR => 'pen-tool',
            self::MANAGER => 'briefcase',
            self::ADMIN => 'shield-check',
        };
    }

    /**
     * Spatie permissions for this level.
     * Each level includes all permissions of lower levels.
     */
    public function permissions(): array
    {
        return match ($this) {
            self::OBSERVER => [
                'view-projects',
                'view-budgets',
            ],
            self::CONTRIBUTOR => [
                ...self::OBSERVER->permissions(),
                'edit-activities',
                'track-progress',
                'create-projects',
            ],
            self::MANAGER => [
                ...self::CONTRIBUTOR->permissions(),
                'edit-projects',
                'manage-activities',
                'manage-budgets',
                'delete-projects',
            ],
            self::ADMIN => [
                ...self::MANAGER->permissions(),
                'validate-projects',
                'manage-organization',
                'manage-users',
                'manage-roles',
                'invite-users',
                'manage-invitations',
            ],
        };
    }

    /**
     * The Spatie role name for this level.
     */
    public function spatieRole(): string
    {
        return match ($this) {
            self::OBSERVER => 'MEMBER',
            self::CONTRIBUTOR => 'MEMBER',
            self::MANAGER => 'MANAGER',
            self::ADMIN => 'ORG_ADMIN',
        };
    }

    /**
     * The AccountType for this level.
     */
    public function accountType(): AccountType
    {
        return match ($this) {
            self::ADMIN => AccountType::ORG_ADMIN,
            default => AccountType::ORG_USER,
        };
    }

    /**
     * Resolve level from a Spatie role name.
     */
    public static function fromSpatieRole(string $role): self
    {
        return match (strtoupper($role)) {
            'ORG_ADMIN' => self::ADMIN,
            'MANAGER' => self::MANAGER,
            'MEMBER' => self::CONTRIBUTOR,
            'SUPERVISOR' => self::MANAGER,
            default => self::OBSERVER,
        };
    }
}
