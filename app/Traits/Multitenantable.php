<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Multitenantable
{
    /**
     * Boot the multitenantable trait.
     * Automatically applies a global scope and sets organization_id on creation.
     */
    protected static function bootMultitenantable(): void
    {
        // 1. Scope Global : Filtrer toutes les requêtes par l'organisation de l'utilisateur connecté
        static::addGlobalScope('organization', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();
                // Si l'utilisateur n'est pas Admin IT et est rattaché à une organisation, on filtre
                if ($user->role !== \App\Enums\AccountType::ADMIN && $user->organization_id) {
                    $builder->where($builder->getQuery()->from . '.organization_id', $user->organization_id);
                }
            } else if (app()->runningInConsole() && !app()->runningUnitTests()) {
                 // Optionnel : En console (migrate/seed), on ne veut pas forcément filtrer
            }
        });

        // 2. Assignation Automatique : Définir organization_id à la création
        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->organization_id) {
                // On ne l'écrase que s'il n'est pas déjà défini manuellement
                if (empty($model->organization_id)) {
                    $model->organization_id = auth()->user()->organization_id;
                }
            }
        });
    }

    /**
     * Relation vers l'organisation.
     */
    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }
}
