<?php

namespace App\Traits;

use App\Enums\AccountType;
use App\Services\OrgContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait Multitenantable
{
    protected static bool $isApplyingMultitenantScope = false;
    protected static array $hasCreatorUserIdCache = [];

    protected static function tableHasCreatorUserId(string $table): bool
    {
        if (!isset(static::$hasCreatorUserIdCache[$table])) {
            static::$hasCreatorUserIdCache[$table] = Schema::hasColumn($table, 'creator_user_id');
        }
        return static::$hasCreatorUserIdCache[$table];
    }

    protected static function bootMultitenantable(): void
    {
        // ── Global Scope : filtre automatique par org ──
        static::addGlobalScope('organization', function (Builder $builder) {
            if (static::$isApplyingMultitenantScope) {
                return;
            }

            static::$isApplyingMultitenantScope = true;

            try {
                if (!auth()->check()) {
                    return;
                }

                $user = auth()->user();
                if (!$user) {
                    return;
                }

                $table = $builder->getQuery()->from;

                // 1. ROOT
                if ($user->role === AccountType::ROOT) {
                    $actingOrgId = session('acting_as_organization_id');
                    if ($actingOrgId) {
                        if ($table === 'users') {
                            // Filtre par org MAIS autorise ROOT lui-meme
                            $builder->where(function ($q) use ($table, $actingOrgId, $user) {
                                $q->where($table . '.organization_id', $actingOrgId)
                                  ->orWhere($table . '.id', $user->id);
                            });
                        } else {
                            $builder->where($table . '.organization_id', $actingOrgId);
                        }
                    }
                    // ROOT libre → pas de filtre (voit tout)
                    return;
                }

                // 2. Pas de role (onboarding)
                if ($user->role === null) {
                    return;
                }

                // 3. User avec org
                if ($user->organization_id) {
                    $builder->where($table . '.organization_id', $user->organization_id);
                    return;
                }

                // 4. Independent
                if ($user->is_independent) {
                    if (static::tableHasCreatorUserId($table)) {
                        $builder->where($table . '.creator_user_id', $user->id);
                    } else {
                        $builder->whereRaw('1 = 0');
                    }
                    return;
                }

                // 5. Aucun cas matche → rien visible
                $builder->whereRaw('1 = 0');

            } finally {
                static::$isApplyingMultitenantScope = false;
            }
        });

        // ── Creating : injecte org_id + creator_user_id auto ──
        static::creating(function ($model) {
            if (!auth()->check()) {
                return;
            }

            // Utilise OrgContext pour l'org_id
            $orgId = OrgContext::orgId();
            if ($orgId && empty($model->organization_id)) {
                $model->organization_id = $orgId;
            }

            // Creator user_id
            if (empty($model->creator_user_id) && static::tableHasCreatorUserId($model->getTable())) {
                $model->creator_user_id = auth()->id();
            }
        });
    }

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }
}
