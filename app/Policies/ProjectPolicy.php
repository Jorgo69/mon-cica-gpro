<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
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

        if ($user->hasRole('IT_ADMIN')) {
            return true;
        }

        return $user->organization_id === $project->organization_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
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

        if ($user->hasRole('IT_ADMIN')) {
            return true;
        }

        return $user->organization_id === $project->organization_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('delete-projects')) {
            return false;
        }

        if ($user->hasRole('IT_ADMIN')) {
            return true;
        }

        return $user->organization_id === $project->organization_id;
    }
    
    /**
     * Determine whether the user can validate the model.
     */
    public function validate(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('validate-projects')) {
            return false;
        }

        if ($user->hasRole('IT_ADMIN')) {
            return true;
        }

        return $user->organization_id === $project->organization_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('delete-projects')) {
            return false;
        }

        if ($user->hasRole('IT_ADMIN')) {
            return true;
        }

        return $user->organization_id === $project->organization_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        if (!$user->hasPermissionTo('delete-projects')) {
            return false;
        }

        if ($user->hasRole('IT_ADMIN')) {
            return true;
        }

        return $user->organization_id === $project->organization_id;
    }
}
