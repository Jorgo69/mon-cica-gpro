<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Multitenantable
{
    protected static bool $isApplyingMultitenantScope = false;

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

                    if ($user && $user->role === \App\Enums\AccountType::SYSTEM_ADMIN) {
                        // Voit tout
                    } elseif ($user && $user->role === null) {
                        // En attente d'onboarding : pas de filtre (redirige vers onboarding par le middleware)
                    } elseif ($user && $user->organization_id) {
                        $builder->where($builder->getQuery()->from . '.organization_id', $user->organization_id);
                    } elseif ($user && $user->is_independent) {
                        // Independant : voit ses propres donnees via creator_user_id
                        $model = $builder->getModel();
                        if (in_array('creator_user_id', $model->getFillable())) {
                            $builder->where($builder->getQuery()->from . '.creator_user_id', $user->id);
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

                if ($user->organization_id && empty($model->organization_id)) {
                    $model->organization_id = $user->organization_id;
                }

                if (empty($model->creator_user_id) && in_array('creator_user_id', $model->getFillable())) {
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
