<?php

namespace App\Services\Queries;

use App\Enums\AccountType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserQueryService
{
    /**
     * Retourne un query builder User scope a l'organisation courante.
     * ROOT voit tout, org_admin/org_user voient leur org, independent ne voit que soi.
     */
    public static function forCurrentOrg(): Builder
    {
        $user = auth()->user();

        if (!$user) {
            return User::query()->whereRaw('1 = 0');
        }

        if ($user->role === AccountType::ROOT) {
            return User::query();
        }

        if ($user->organization_id) {
            return User::query()->where('organization_id', $user->organization_id);
        }

        if ($user->is_independent) {
            return User::query()->where('id', $user->id);
        }

        return User::query()->whereRaw('1 = 0');
    }
}
