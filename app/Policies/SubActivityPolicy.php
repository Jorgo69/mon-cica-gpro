<?php

namespace App\Policies;

use App\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\SubActivity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubActivityPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tout le monde peut consulter la liste des sous-activités.
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubActivity $subActivity): bool
    {
        // Tout le monde peut voir une sous-activité spécifique.
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Activity $activity): bool
    {
        // Pour la création, on peut autoriser tous les utilisateurs authentifiés, ou restreindre davantage si nécessaire.
        // Ici, nous autorisons tous les utilisateurs authentifiés à créer.
        $isAdmin = $user->role === 'Administrateur';
        $isProjectCreator = $user->id === optional(
            $activity->result
                     ->specificObjective
                     ->logicalFramework
                     ->project
        )->creator_user_id;

        return $isAdmin || $isProjectCreator;
        
        return $user->role === 'Administrateur'|| $user->id === $activity->responsible_user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity, SubActivity $subActivity): bool
    {
        // L'utilisateur peut indiquer s'il est l'administrateur OU s'il est responsable de l'activité parente.
        // Cela nécessite de vérifier l'utilisateur responsable de l'activité parente.
        return $user->role === 'Administrateur' || $user->id === $activity->responsible_user_id || $user->id === $subActivity->activity->responsible_user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubActivity $subActivity): bool
    {
        // Même logique que la mise à jour : administrateur ou responsable de l'activité parent.
        return $user->role === 'Administrateur' || $user->id === $subActivity->activity->responsible_user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubActivity $subActivity): bool
    {
         // La restauration suit généralement les mêmes règles que la suppression/mise à jour.
        return $user->role === 'Administrateur' || $user->id === $subActivity->activity->responsible_user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubActivity $subActivity): bool
    {
        // La suppression forcée est généralement une opération réservée à l'administrateur.
        return $user->role === 'Administrateur';
    }
}
