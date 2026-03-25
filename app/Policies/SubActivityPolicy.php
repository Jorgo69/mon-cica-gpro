<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubActivityPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-projects');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $subActivity): bool
    {
        if (!$user->hasPermissionTo('view-projects')) {
            return false;
        }

        if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return true;
        }

        // Vérifier que la sous-activité appartient à l'organisation de l'utilisateur
        // On suppose que SubActivity n'a pas directement organization_id, on passe par activity
        return session('current_organization_id') === (string) $subActivity->activity->project?->organization_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Activity $activity): bool
    {
        if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return false;
        }

        if (!$user->hasPermissionTo('manage-activities')) {
            return false;
        }

        return session('current_organization_id') === (string) $activity->project?->organization_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity, Activity $subActivity): bool
    {
        if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return false;
        }

        if (!$user->hasPermissionTo('manage-activities')) {
            return false;
        }

        return session('current_organization_id') === (string) $activity->project?->organization_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $subActivity): bool
    {
        if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return false;
        }

        if (!$user->hasPermissionTo('manage-activities')) {
            return false;
        }

        return session('current_organization_id') === (string) $subActivity->activity->project?->organization_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Activity $subActivity): bool
    {
         // La restauration suit généralement les mêmes règles que la suppression/mise à jour.
        return $user->account_type === 'Administrateur' || $user->id === $subActivity->activity->responsible_user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Activity $subActivity): bool
    {
        // La suppression forcée est généralement une opération réservée à l'administrateur.
        return $user->account_type === 'Administrateur';
    }
}
