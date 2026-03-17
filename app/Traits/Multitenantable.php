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
            // Empêcher la récursion si on est déjà en train de résoudre l'utilisateur
            // ou si on est en train de charger un modèle User (le User ne doit pas se filtrer lui-même via auth()->user())
            if (static::$isAuthResolutionInProgress ?? false) {
                return;
            }

            if (auth()->check()) {
                $user = auth()->user();
                
                // Si l'utilisateur n'est pas Admin IT et est rattaché à une organisation, on filtre
                if ($user && $user->role !== \App\Enums\AccountType::ADMIN && $user->organization_id) {
                    $builder->where($builder->getQuery()->from . '.organization_id', $user->organization_id);
                }
            }
        });

        // 2. Assignation Automatique : Définir organization_id et creator_user_id à la création
        static::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();
                
                // Assignation de l'organisation
                if ($user->organization_id && empty($model->organization_id)) {
                    $model->organization_id = $user->organization_id;
                }

                // Assignation du créateur si la colonne existe
                if (empty($model->creator_user_id)) {
                    // Vérifier si le modèle a la colonne creator_user_id (standardisé)
                    // Note: Schema::hasColumn est coûteux, on peut juste tenter l'assignation si c'est dans $fillable
                    if (in_array('creator_user_id', $model->getFillable())) {
                        $model->creator_user_id = $user->id;
                    }
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
