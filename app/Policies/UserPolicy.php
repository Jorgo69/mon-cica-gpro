<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\AccountType;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    private function belongsToCurrentOrg(User $model): bool
    {
        $orgId = session('current_organization_id');
        if (!$orgId) return false;

        return $model->organizations()->where('organizations.id', $orgId)->exists();
    }

    /** Un org_admin peut gérer les users même sans permission Spatie explicite. */
    private function canManageUsers(User $user): bool
    {
        return $user->account_type === AccountType::ORG_ADMIN
            || $user->hasPermissionTo('manage-users');
    }

    public function viewAny(User $user): bool
    {
        if ($user->account_type === AccountType::SYSTEM_ADMIN) return true;
        return $this->canManageUsers($user);
    }

    public function view(User $user, User $model): bool
    {
        if ($user->account_type === AccountType::SYSTEM_ADMIN) return true;
        return $this->canManageUsers($user) && $this->belongsToCurrentOrg($model);
    }

    public function create(User $user): bool
    {
        if ($user->account_type === AccountType::SYSTEM_ADMIN) return false;
        return $this->canManageUsers($user);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->account_type === AccountType::SYSTEM_ADMIN) return false;
        return $this->canManageUsers($user) && $this->belongsToCurrentOrg($model);
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->account_type === AccountType::SYSTEM_ADMIN) return false;
        if ($user->id === $model->id) return false;
        return $this->canManageUsers($user) && $this->belongsToCurrentOrg($model);
    }
}
