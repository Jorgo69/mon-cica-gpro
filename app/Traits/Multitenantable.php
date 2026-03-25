<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Scope global multi-tenant par organisation.
 * À utiliser uniquement sur les modèles ayant une colonne organization_id directe :
 * Project, ProjectType, DynamicProjectField, Category, Department.
 *
 * Les modèles enfants (Activity, Resource, etc.) sont sécurisés par la chaîne FK.
 * L'organisation active est lue depuis session('current_organization_id').
 */
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
                if (!auth()->check()) {
                    return;
                }

                $user = auth()->user();

                // system_admin voit tout, sans restriction
                if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
                    return;
                }

                $orgId = session('current_organization_id');

                if ($orgId) {
                    $builder->where($builder->getQuery()->from . '.organization_id', $orgId);
                } else {
                    // Aucune organisation active en session → sécurité : bloquer
                    $builder->whereRaw('1 = 0');
                }
            } finally {
                static::$isApplyingMultitenantScope = false;
            }
        });

        static::creating(function ($model) {
            if (!auth()->check()) {
                return;
            }

            if (empty($model->organization_id)) {
                $orgId = session('current_organization_id');
                if ($orgId) {
                    $model->organization_id = $orgId;
                }
            }

            if (empty($model->creator_user_id) && in_array('creator_user_id', $model->getFillable())) {
                $model->creator_user_id = auth()->id();
            }
        });
    }

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }
}
