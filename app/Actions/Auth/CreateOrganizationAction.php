<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\Organization;
use App\Models\Role;
use Illuminate\Support\Str;

class CreateOrganizationAction
{
    /**
     * Create an organization and assign the user as ORG_ADMIN.
     */
    public function execute(User $user, string $name): Organization
    {
        // 1. Créer l'organisation
        $organization = Organization::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => 'trial',
        ]);

        // 2. Rattacher l'utilisateur à l'organisation
        $user->update([
            'organization_id' => $organization->id,
            'role' => 'admin', // Rôle légué (compatibilité)
        ]);

        // 3. Assigner le rôle Spatie ORG_ADMIN (Multi-tenant context)
        // Note: On s'assure que le context Spatie est bien sur l'ID de la nouvelle organisation
        setPermissionsTeamId($organization->id);
        $user->assignRole('ORG_ADMIN');

        return $organization;
    }
}
