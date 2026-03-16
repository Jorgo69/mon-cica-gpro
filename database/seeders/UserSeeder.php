<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::where('slug', 'cica-pro')->first();
        if (!$organization) {
            return;
        }

        $orgId = $organization->id;

        // Activer le contexte de l'organisation pour Spatie Teams
        setPermissionsTeamId($orgId);

        // 1. Créer les Rôles spécifiques à cette organisation
        $roles = [
            'ORG_ADMIN' => [
                'manage-organization', 'manage-users', 'manage-roles', 
                'view-projects', 'create-projects', 'edit-projects', 'delete-projects', 'validate-projects',
                'manage-activities', 'track-progress', 'view-budgets', 'manage-budgets'
            ],
            'MANAGER' => [
                'view-projects', 'create-projects', 'edit-projects', 
                'manage-activities', 'track-progress', 'view-budgets'
            ],
            'MEMBER' => [
                'view-projects', 'track-progress'
            ],
            'SUPERVISOR' => [
                'view-projects', 'track-progress', 'view-budgets', 'validate-projects'
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                'organization_id' => $orgId
            ]);
            $role->syncPermissions($permissions);
        }

        // 2. Créer les Utilisateurs et assigner les rôles

        // IT Admin
        $itAdmin = User::firstOrCreate(
            ['email' => 'admin@cave-tech.com'],
            [
                'name' => 'Jean Dupont',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'organization_id' => $orgId,
                'sexe' => 'Homme',
                'role' => \App\Enums\AccountType::ADMIN,
                'department' => 'Informatique & Systèmes',
                'telephone' => '+229 97 00 01 02',
                'pays' => 'Bénin',
                'ville' => 'Cotonou',
            ]
        );
        $itAdmin->assignRole('IT_ADMIN'); // Rôle global

        // Org Admin (Superviseurs dans le seed original)
        $supervisors = [
            ['name' => 'Alice Dossou', 'email' => 'alice.d@cpro.org', 'sexe' => 'Femme'],
        ];

        foreach ($supervisors as $supervisor) {
            $user = User::firstOrCreate(
                ['email' => $supervisor['email']],
                [
                    'name' => $supervisor['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'organization_id' => $orgId,
                    'sexe' => $supervisor['sexe'],
                    'role' => \App\Enums\AccountType::SUPERVISOR,
                    'department' => 'Direction stratégique',
                    'pays' => 'Bénin',
                    'ville' => 'Porto-Novo',
                ]
            );
            $user->assignRole('SUPERVISOR');
        }

        // Project Manager
        $manager = User::firstOrCreate(
            ['email' => 'p.manager@cpro.org'],
            [
                'name' => 'Sophie Koumé',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'organization_id' => $orgId,
                'sexe' => 'Femme',
                'role' => \App\Enums\AccountType::MANAGER,
                'department' => 'Gestion de Projets',
                'telephone' => '+229 96 11 22 33',
                'pays' => 'Bénin',
                'ville' => 'Abomey-Calavi',
            ]
        );
        $manager->assignRole('MANAGER');

        // Membres
        $members = [
            ['name' => 'Idriss Gnonlon', 'email' => 'idriss.g@cpro.org', 'sexe' => 'Homme'],
            ['name' => 'Carine Sika', 'email' => 'carine.s@cpro.org', 'sexe' => 'Femme'],
        ];

        foreach ($members as $member) {
            $user = User::firstOrCreate(
                ['email' => $member['email']],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'organization_id' => $orgId,
                    'sexe' => $member['sexe'],
                    'role' => \App\Enums\AccountType::MEMBER,
                    'department' => 'Terrain & Opérations',
                    'pays' => 'Bénin',
                    'ville' => 'Parakou',
                ]
            );
            $user->assignRole('MEMBER');
        }
    }
}
