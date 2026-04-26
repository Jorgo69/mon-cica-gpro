<?php

namespace App\Services\Queries;

use App\Enums\AccountType;
use App\Models\User;
use App\Services\OrgContext;
use Illuminate\Database\Eloquent\Builder;

class UserQueryService
{
    /**
     * Retourne un query builder User scope a l'organisation courante.
     * Utilise OrgContext pour respecter l'impersonation ROOT.
     */
    public static function forCurrentOrg(): Builder
    {
        $user = auth()->user();

        if (!$user) {
            return User::query()->whereRaw('1 = 0');
        }

        // ROOT : si en impersonation, filtre par org cible ; sinon voit tout
        if ($user->role === AccountType::ROOT) {
            $actingOrgId = OrgContext::orgId();
            if ($actingOrgId && OrgContext::isImpersonating()) {
                return User::query()->where('organization_id', $actingOrgId);
            }
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
