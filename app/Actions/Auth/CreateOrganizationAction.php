<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\Organization;
use App\Enums\AccountType;
use App\Enums\OrgMemberRole;
use App\Enums\OrganizationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\PermissionRegistrar;

class CreateOrganizationAction
{
    /**
     * Permissions par rôle Spatie — à attribuer dans la nouvelle org.
     * Source de vérité : PermissionSeeder.
     */
    private const ROLE_PERMISSIONS = [
        'ORG_ADMIN' => [
            'manage-organization', 'manage-users', 'manage-roles',
            'view-projects', 'create-projects', 'edit-projects', 'delete-projects', 'validate-projects',
            'manage-activities', 'track-progress', 'view-budgets', 'manage-budgets',
        ],
        'MANAGER' => [
            'view-projects', 'create-projects', 'edit-projects',
            'manage-activities', 'track-progress', 'view-budgets',
        ],
        'MEMBER' => [
            'view-projects', 'track-progress',
        ],
        'SUPERVISOR' => [
            'view-projects', 'track-progress', 'view-budgets', 'validate-projects',
        ],
    ];

    public function execute(User $user, string $name): Organization
    {
        return DB::transaction(function () use ($user, $name) {
            Log::info('[Action] CreateOrganization - Début', ['user_id' => $user->id]);

            // 1. Créer l'organisation
            $organization = Organization::create([
                'name'   => $name,
                'type'   => OrganizationType::HEADQUARTERS,
                'status' => 'trial',
            ]);

            // 2. Marquer le user comme org_admin (il crée son organisation)
            $user->update(['account_type' => AccountType::ORG_ADMIN]);

            // 3. Lier via la table pivot
            $user->organizations()->attach($organization->id, [
                'role'      => OrgMemberRole::ORG_ADMIN->value,
                'status'    => 'active',
                'joined_at' => now(),
            ]);

            // 4. Organisation active en session
            session(['current_organization_id' => $organization->id]);

            // 5. Créer les rôles Spatie scopés à cette nouvelle org + assigner ORG_ADMIN
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            setPermissionsTeamId($organization->id);

            foreach (self::ROLE_PERMISSIONS as $roleName => $permissions) {
                $role = Role::firstOrCreate([
                    'name'            => $roleName,
                    'guard_name'      => 'web',
                    'organization_id' => $organization->id,
                ]);
                $role->syncPermissions($permissions);
            }

            // Assigner ORG_ADMIN au créateur dans le contexte de cette org
            $user->assignRole('ORG_ADMIN');

            Log::info('[Action] CreateOrganization - Terminé', ['org_id' => $organization->id]);

            return $organization;
        });
    }
}
