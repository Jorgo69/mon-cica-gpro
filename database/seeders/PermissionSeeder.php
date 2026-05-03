<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Créer les Permissions (Globales)
        $permissions = [
            // Projets
            'view-projects',
            'create-projects',
            'edit-projects',
            'delete-projects',
            'validate-projects',
            
            // Activités
            'manage-activities',
            'track-progress',
            
            // Organisation & Users
            'manage-organization',
            'manage-users',
            'manage-roles',
            
            // Finances
            'view-budgets',
            'manage-budgets',
            
            // System & Config
            'access-admin-panel',
            'manage-system-config',
            'view-audit-logs',

            // Invitations
            'invite-users',
            'manage-invitations',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Créer les Rôles Globaux (organization_id = null)
        
        // IT_ADMIN : Accès total
        $itAdmin = Role::firstOrCreate(
            ['name' => 'IT_ADMIN', 'guard_name' => 'web', 'organization_id' => null]
        );
        $itAdmin->syncPermissions($permissions);

        // ORG_ADMIN : Administrateur d'organisation
        $orgAdmin = Role::firstOrCreate(
            ['name' => 'ORG_ADMIN', 'guard_name' => 'web', 'organization_id' => null]
        );
        $orgAdmin->syncPermissions([
            'manage-organization', 'manage-users', 'manage-roles',
            'view-projects', 'create-projects', 'edit-projects', 'delete-projects', 'validate-projects',
            'manage-activities', 'track-progress', 'view-budgets', 'manage-budgets',
            'invite-users', 'manage-invitations'
        ]);

        // MANAGER : Gestionnaire de projets
        $manager = Role::firstOrCreate(
            ['name' => 'MANAGER', 'guard_name' => 'web', 'organization_id' => null]
        );
        $manager->syncPermissions([
            'view-projects', 'create-projects', 'edit-projects', 
            'manage-activities', 'track-progress', 'view-budgets'
        ]);

        // MEMBER : Membre d'équipe
        $member = Role::firstOrCreate(
            ['name' => 'MEMBER', 'guard_name' => 'web', 'organization_id' => null]
        );
        $member->syncPermissions([
            'view-projects', 'create-projects', 'track-progress'
        ]);

        // SUPERVISOR : Superviseur / Bailleur
        $supervisor = Role::firstOrCreate(
            ['name' => 'SUPERVISOR', 'guard_name' => 'web', 'organization_id' => null]
        );
        $supervisor->syncPermissions([
            'view-projects', 'track-progress', 'view-budgets', 'validate-projects'
        ]);
    }
}
