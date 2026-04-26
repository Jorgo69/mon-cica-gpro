<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait Multitenantable
{
    protected static bool $isApplyingMultitenantScope = false;
    protected static array $hasCreatorUserIdCache = [];

    /**
     * Verifie si la table a une colonne creator_user_id (avec cache en memoire).
     */
    protected static function tableHasCreatorUserId(string $table): bool
    {
        if (!isset(static::$hasCreatorUserIdCache[$table])) {
            static::$hasCreatorUserIdCache[$table] = Schema::hasColumn($table, 'creator_user_id');
        }
        return static::$hasCreatorUserIdCache[$table];
    }

    protected static function bootMultitenantable(): void
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            if (static::$isApplyingMultitenantScope) {
                return;
            }

            static::$isApplyingMultitenantScope = true;

            try {
                if (auth()->check()) {
                    $user = auth()->user();

                    if ($user && $user->role === \App\Enums\AccountType::ROOT) {
                        $actingOrgId = session('acting_as_organization_id');
                        if ($actingOrgId) {
                            $table = $builder->getQuery()->from;
                            if ($table === 'users') {
                                // Pour les users : filtre par org MAIS autorise ROOT lui-même
                                $builder->where(function ($q) use ($table, $actingOrgId, $user) {
                                    $q->where($table . '.organization_id', $actingOrgId)
                                      ->orWhere($table . '.id', $user->id);
                                });
                            } else {
                                $builder->where($table . '.organization_id', $actingOrgId);
                            }
                        }
                        // Sinon ROOT voit tout (pas de filtre)
                    } elseif ($user && $user->role === null) {
                        // En attente d'onboarding : pas de filtre
                    } elseif ($user && $user->organization_id) {
                        $builder->where($builder->getQuery()->from . '.organization_id', $user->organization_id);
                    } elseif ($user && $user->is_independent) {
                        $table = $builder->getQuery()->from;
                        if (static::tableHasCreatorUserId($table)) {
                            $builder->where($table . '.creator_user_id', $user->id);
                        } else {
                            $builder->whereRaw('1 = 0');
                        }
                    } elseif ($user) {
                        $builder->whereRaw('1 = 0');
                    }
                }
            } finally {
                static::$isApplyingMultitenantScope = false;
            }
        });

        static::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();

                // ROOT en impersonation : utilise l'org cible
                $orgId = $user->organization_id;
                if ($user->role === \App\Enums\AccountType::ROOT && session('acting_as_organization_id')) {
                    $orgId = session('acting_as_organization_id');
                }

                if ($orgId && empty($model->organization_id)) {
                    $model->organization_id = $orgId;
                }

                if (empty($model->creator_user_id) && static::tableHasCreatorUserId($model->getTable())) {
                    $model->creator_user_id = $user->id;
                }
            }
        });
    }

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }
}
