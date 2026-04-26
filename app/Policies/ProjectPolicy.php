<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    use \Illuminate\Auth\Access\HandlesAuthorization;
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
    public function view(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('view-projects')) {
            return false;
        }

        // Le SYSTEM_ADMIN peut tout voir pour le support technique
        if ($user->role === \App\Enums\AccountType::ROOT) {
            return true;
        }

        return (string) $user->organization_id === (string) $project->organization_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Interdire la création au SYSTEM_ADMIN sur les données d'organisations
        if ($user->role === \App\Enums\AccountType::ROOT) {
            return false;
        }

        return $user->hasPermissionTo('create-projects');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('edit-projects')) {
            return false;
        }

        // Le SYSTEM_ADMIN ne doit pas modifier les données privées des organisations
        if ($user->role === \App\Enums\AccountType::ROOT) {
            return false;
        }

        return (string) $user->organization_id === (string) $project->organization_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('delete-projects')) {
            return false;
        }

        // Le SYSTEM_ADMIN ne doit pas supprimer les données privées des organisations
        if ($user->role === \App\Enums\AccountType::ROOT) {
            return false;
        }

        return (string) $user->organization_id === (string) $project->organization_id;
    }
    
    /**
     * Determine whether the user can validate the model.
     */
    public function validate(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('validate-projects')) {
            return false;
        }

        if ($user->role === \App\Enums\AccountType::ROOT) {
            return false;
        }

        return (string) $user->organization_id === (string) $project->organization_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('delete-projects')) {
            return false;
        }

        if ($user->role === \App\Enums\AccountType::ROOT) {
            return false;
        }

        return (string) $user->organization_id === (string) $project->organization_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('delete-projects')) {
            return false;
        }

        if ($user->role === \App\Enums\AccountType::ROOT) {
            return false;
        }

        return (string) $user->organization_id === (string) $project->organization_id;
    }
}
