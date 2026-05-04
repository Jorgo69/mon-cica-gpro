<?php

namespace Database\Seeders;

use App\Enums\PermissionLevel;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create all permissions
        $permissions = [
            'view-projects',
            'create-projects',
            'edit-projects',
            'delete-projects',
            'validate-projects',
            'manage-activities',
            'track-progress',
            'manage-organization',
            'manage-users',
            'manage-roles',
            'view-budgets',
            'manage-budgets',
            'access-admin-panel',
            'manage-system-config',
            'view-audit-logs',
            'invite-users',
            'manage-invitations',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. IT_ADMIN : Full access (ROOT)
        $itAdmin = Role::firstOrCreate(
            ['name' => 'IT_ADMIN', 'guard_name' => 'web', 'organization_id' => null]
        );
        $itAdmin->syncPermissions($permissions);

        // 3. Sync roles from PermissionLevel enum
        foreach (PermissionLevel::cases() as $level) {
            $role = Role::firstOrCreate(
                ['name' => $level->spatieRole(), 'guard_name' => 'web', 'organization_id' => null]
            );
            $role->syncPermissions($level->permissions());
        }

        // 4. SUPERVISOR : special role for funders (bailleurs)
        $supervisor = Role::firstOrCreate(
            ['name' => 'SUPERVISOR', 'guard_name' => 'web', 'organization_id' => null]
        );
        $supervisor->syncPermissions([
            'view-projects', 'track-progress', 'view-budgets', 'validate-projects',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
