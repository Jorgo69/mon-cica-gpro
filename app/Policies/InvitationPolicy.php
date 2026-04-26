<?php

namespace App\Policies;

use App\Enums\AccountType;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if ($user->role === AccountType::ROOT) return true;
        return $user->hasPermissionTo('manage-invitations');
    }

    public function create(User $user): bool
    {
        if ($user->role === AccountType::ROOT) return true;
        return $user->hasPermissionTo('invite-users');
    }

    public function delete(User $user, Invitation $invitation): bool
    {
        if ($user->role === AccountType::ROOT) return true;
        return $user->hasPermissionTo('manage-invitations')
            && (string) $user->organization_id === (string) $invitation->organization_id;
    }
}
