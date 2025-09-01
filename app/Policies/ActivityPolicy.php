<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActivityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tous les utilisateurs peuvent voir les activités.
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Détermine si l'utilisateur peut voir le modèle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Activity $activity): bool
    {
        // Tous les utilisateurs peuvent voir les activités.
        return true;
    }

    /**
     * Determine whether the user can create models.
     * Détermine si l'utilisateur peut créer une activité.
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Activity $activity): bool
    {
        $isAdmin = $user->role === 'Administrateur';

        $project = optional(
            $activity->result
                    ->specificObjective
                    ->logicalFramework
                    ->project
        );

        if (!$project) {
            return false; // pas de projet lié → refus
        }

        // Normalisation du statut
        $status = trim($project->status ?? '');
        $isDraft    = strcasecmp($status, 'brouillon') === 0;
        $isFinished = strcasecmp($status, 'terminé') === 0;

        $isProjectCreator      = $user->id === $project->creator_user_id;
        $isActivityResponsible = $user->id === $activity->responsible_user_id;

        // Admin passe toujours
        if ($isAdmin) {
            return true;
        }

        // Créateur ou responsable → accès interdit si brouillon ou terminé
        if ($isProjectCreator || $isActivityResponsible) {
            return !($isDraft || $isFinished);
        }

        // Tous les autres refusés
        return false;
    }

    /**
     * Determine whether the user can update the model.
     * Détermine si l'utilisateur peut mettre à jour le modèle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Activity $activity): bool
    {
        $isResponsible = $user->id === $activity->responsible_user_id;
        $isAdmin = $user->role === 'Administrateur';
        $isProjectCreator = $user->id === optional(
            $activity->result
                     ->specificObjective
                     ->logicalFramework
                     ->project
        )->creator_user_id;

        return $isResponsible || $isProjectCreator || $isAdmin;
    }

    /**
     * Determine whether the user can delete the model.
     * Détermine si l'utilisateur peut supprimer le modèle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Activity $activity): bool
    {
        // L'utilisateur peut supprimer si c'est l'utilisateur responsable OU s'il est administrateur.
        return $this->update($user, $activity);
    }

    /**
     * Determine whether the user can restore the model.
     * Détermine si l'utilisateur peut restaurer le modèle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Activity $activity): bool
    {
        // L'utilisateur peut restaurer s'il est administrateur.
        return $user->role === 'Administrateur';
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Détermine si l'utilisateur peut forcer la suppression du modèle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        // L'utilisateur peut forcer la suppression s'il est administrateur.
        return $user->role === 'Administrateur';
    }
}
