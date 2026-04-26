<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage-organization');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organization $organization): bool
    {
        if ($user->role === \App\Enums\AccountType::ROOT) {
            return true;
        }

        return (string) $user->organization_id === (string) $organization->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seul le SYSTEM_ADMIN peut créer de nouvelles organisations
        return $user->role === \App\Enums\AccountType::ROOT;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organization $organization): bool
    {
        if ($user->role === \App\Enums\AccountType::ROOT) {
            return true;
        }

        if (!$user->hasPermissionTo('manage-organization')) {
            return false;
        }

        return (string) $user->organization_id === (string) $organization->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Organization $organization): bool
    {
        return $user->role === \App\Enums\AccountType::ROOT;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Organization $organization): bool
    {
        return $user->role === \App\Enums\AccountType::ROOT;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Organization $organization): bool
    {
        return $user->role === \App\Enums\AccountType::ROOT;
    }
}
