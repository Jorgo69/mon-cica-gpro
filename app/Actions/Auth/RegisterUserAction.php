<?php

namespace App\Actions\Auth;

use App\Enums\AccountType;
use App\Enums\OrganizationStatus;
use App\Enums\PermissionLevel;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        // Selfhosted + first user → auto ORG_ADMIN with org
        if (isSelfHosted() && User::count() === 0) {
            return $this->createFirstAdmin($data);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'organization_id' => null,
        ]);

        event(new Registered($user));

        return $user;
    }

    protected function createFirstAdmin(array $data): User
    {
        // Ensure permissions exist
        Artisan::call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);

        // Create default organization
        $orgName = config('app.name', 'Mon Organisation');
        $org = Organization::create([
            'name' => $orgName,
            'slug' => Str::slug($orgName),
            'status' => OrganizationStatus::ACTIVE,
        ]);

        // Create admin user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => AccountType::ORG_ADMIN,
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);

        // Set as owner
        $org->update(['owner_user_id' => $user->id]);

        // Assign Spatie role + permissions
        $level = PermissionLevel::ADMIN;
        $user->assignRole($level->spatieRole());
        $user->syncPermissions($level->permissions());

        event(new Registered($user));

        return $user;
    }
}
