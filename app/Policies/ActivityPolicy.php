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
        return $user->hasPermissionTo('view-projects');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $activity): bool
    {
        if (!$user->hasPermissionTo('view-projects')) {
            return false;
        }

        if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return true;
        }

        return session('current_organization_id') === (string) $activity->project?->organization_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return false;
        }

        return $user->hasPermissionTo('manage-activities');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity): bool
    {
        if (!$user->hasPermissionTo('manage-activities')) {
            return false;
        }

        if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return false;
        }

        return session('current_organization_id') === (string) $activity->project?->organization_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $activity): bool
    {
        if (!$user->hasPermissionTo('manage-activities')) {
            return false;
        }

        if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return false;
        }

        return session('current_organization_id') === (string) $activity->project?->organization_id;
    }

    /**
     * Determine whether the user can track progress of the model.
     */
    public function trackProgress(User $user, Activity $activity): bool
    {
        if (!$user->hasPermissionTo('track-progress')) {
            return false;
        }

        if ($user->account_type === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return false;
        }

        return session('current_organization_id') === (string) $activity->project?->organization_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Activity $activity): bool
    {
        return $this->delete($user, $activity);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        return $this->delete($user, $activity);
    }
}
