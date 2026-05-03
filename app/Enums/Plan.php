<?php

namespace App\Enums;

enum Plan: string
{
    case FREE = 'free';
    case PRO = 'pro';
    case ENTERPRISE = 'enterprise';

    public function label(): string
    {
        return match ($this) {
            self::FREE => __('plans.free'),
            self::PRO => __('plans.pro'),
            self::ENTERPRISE => __('plans.enterprise'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::FREE => 'text-muted',
            self::PRO => 'text-accent',
            self::ENTERPRISE => 'text-amber-500',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::FREE => 'gray',
            self::PRO => 'blue',
            self::ENTERPRISE => 'amber',
        };
    }

    public function limits(): array
    {
        return config("gpro.plans.{$this->value}.limits", []);
    }

    public function maxProjects(): int
    {
        return $this->limits()['max_projects'] ?? 1;
    }

    public function maxMembers(): int
    {
        return $this->limits()['max_members'] ?? 3;
    }

    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->limits()['features'] ?? []);
    }

    public function price(): string
    {
        return config("gpro.plans.{$this->value}.price", '0');
    }
}
