<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Multitenantable
{
    /**
     * Cache pour éviter la récursion infinie lors de la résolution de l'utilisateur.
     */
    protected static bool $isApplyingMultitenantScope = false;

    /**
     * Boot the multitenantable trait.
     * Automatically applies a global scope and sets organization_id on creation.
     */
    protected static function bootMultitenantable(): void
    {
        // 1. Scope Global : Filtrer toutes les requêtes par l'organisation de l'utilisateur connecté
        static::addGlobalScope('organization', function (Builder $builder) {
            // Empêcher la récursion infinie (notamment sur le modèle User)
            if (static::$isApplyingMultitenantScope) {
                return;
            }

            // Marquer le début du scope
            static::$isApplyingMultitenantScope = true;

            try {
                if (auth()->check()) {
                    $user = auth()->user();
                    
                    // SEUL le SYSTEM_ADMIN (Root) bypass l'isolation.
                    if ($user && $user->role !== \App\Enums\AccountType::SYSTEM_ADMIN) {
                        // Si l'utilisateur appartient à une organisation, on l'isole strictement
                        if ($user->organization_id) {
                            $builder->where($builder->getQuery()->from . '.organization_id', $user->organization_id);
                        } else if (!$user->is_independent) {
                            // Si pas d'organisation et pas indépendant, on bloque par défaut
                            $builder->whereRaw('1 = 0');
                        }
                    }
                }
            } finally {
                // Toujours libérer le verrou
                static::$isApplyingMultitenantScope = false;
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
