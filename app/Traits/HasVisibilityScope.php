<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for models with system/global/org visibility.
 *
 * Requires columns: is_system (bool), is_active (bool), organization_id (uuid nullable).
 *
 * Visibility rules:
 * - is_system=true, org_id=null  → system (seeded, visible by all, undeletable)
 * - is_system=false, org_id=null → global (created by ROOT, visible by all)
 * - is_system=false, org_id=X   → org-specific (visible only by org X)
 */
trait HasVisibilityScope
{
    public function scopeVisibleForOrg(Builder $query, ?string $orgId): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) use ($orgId) {
                $q->whereNull('organization_id'); // system + global
                if ($orgId) {
                    $q->orWhere('organization_id', $orgId); // org-specific
                }
            });
    }

    public function scopeSystemOnly(Builder $query): Builder
    {
        return $query->where('is_system', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getIsSystemAttribute($value): bool
    {
        return (bool) $value;
    }

    public function isDeletable(): bool
    {
        return !$this->is_system;
    }
}
