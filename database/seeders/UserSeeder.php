<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Organization;
use App\Enums\AccountType;
use App\Enums\OrgMemberRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::where('slug', 'cica-pro')->first();
        if (!$organization) {
            return;
        }

        $orgId = $organization->id;

        // Rôles Spatie scopés à l'organisation (teams)
        setPermissionsTeamId($orgId);

        $rolesPermissions = [
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

        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name'            => $roleName,
                'guard_name'      => 'web',
                'organization_id' => $orgId,
            ]);
            $role->syncPermissions($permissions);
        }

        // System Admin (pas de contexte d'org)
        setPermissionsTeamId(null);

        $systemAdmin = User::firstOrCreate(
            ['email' => 'admin@cave-tech.com'],
            [
                'name'             => 'Jean Dupont',
                'password'         => Hash::make('password'),
                'email_verified_at' => now(),
                'account_type'     => AccountType::SYSTEM_ADMIN,
                'country'          => 'BJ',
                'location'         => ['ville' => 'Cotonou'],
                'telephone'        => '+229 97 00 01 02',
            ]
        );
        $systemAdmin->assignRole('IT_ADMIN');

        // Membres de l'organisation
        setPermissionsTeamId($orgId);

        $members = [
            [
                'email'        => 'alice.d@cpro.org',
                'name'         => 'Alice Dossou',
                'account_type' => AccountType::ORG_ADMIN,
                'org_role'     => OrgMemberRole::ORG_ADMIN,
                'spatie_role'  => 'ORG_ADMIN',
                'country'      => 'BJ',
                'location'     => ['ville' => 'Porto-Novo'],
            ],
            [
                'email'        => 'p.manager@cpro.org',
                'name'         => 'Sophie Koumé',
                'account_type' => AccountType::ORG_MEMBER,
                'org_role'     => OrgMemberRole::MEMBER,
                'spatie_role'  => 'MANAGER',
                'country'      => 'BJ',
                'location'     => ['ville' => 'Abomey-Calavi'],
                'telephone'    => '+229 96 11 22 33',
            ],
            [
                'email'        => 'idriss.g@cpro.org',
                'name'         => 'Idriss Gnonlon',
                'account_type' => AccountType::ORG_MEMBER,
                'org_role'     => OrgMemberRole::MEMBER,
                'spatie_role'  => 'MEMBER',
                'country'      => 'BJ',
                'location'     => ['ville' => 'Parakou'],
            ],
            [
                'email'        => 'carine.s@cpro.org',
                'name'         => 'Carine Sika',
                'account_type' => AccountType::ORG_MEMBER,
                'org_role'     => OrgMemberRole::MEMBER,
                'spatie_role'  => 'MEMBER',
                'country'      => 'BJ',
                'location'     => ['ville' => 'Parakou'],
            ],
        ];

        foreach ($members as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'             => $data['name'],
                    'password'         => Hash::make('password'),
                    'email_verified_at' => now(),
                    'account_type'     => $data['account_type'],
                    'country'          => $data['country'],
                    'location'         => $data['location'],
                    'telephone'        => $data['telephone'] ?? null,
                ]
            );

            // Lier à l'organisation via pivot
            $user->organizations()->syncWithoutDetaching([
                $orgId => [
                    'role'      => $data['org_role']->value,
                    'status'    => 'active',
                    'joined_at' => now(),
                ],
            ]);

            $user->assignRole($data['spatie_role']);
        }

        // Indépendant (pas d'org, workspace personnel)
        User::firstOrCreate(
            ['email' => 'indie@test.com'],
            [
                'name'              => 'Marc Indépendant',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'account_type'      => AccountType::INDEPENDENT,
                'country'           => 'FR',
                'location'          => ['ville' => 'Lyon', 'quartier' => 'Part-Dieu'],
                'telephone'         => '+33 6 12 34 56 78',
            ]
        );
    }
}
