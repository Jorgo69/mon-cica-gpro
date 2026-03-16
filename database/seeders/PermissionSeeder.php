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
            
            // System
            'access-admin-panel',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Créer le rôle IT_ADMIN (Global - organization_id = null)
        $itAdmin = Role::firstOrCreate(
            ['name' => 'IT_ADMIN', 'guard_name' => 'web', 'organization_id' => null]
        );
        $itAdmin->syncPermissions($permissions);
    }
}
