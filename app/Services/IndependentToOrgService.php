<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Enums\OrganizationStatus;
use App\Enums\PermissionLevel;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IndependentToOrgService
{
    public function migrate(User $user, string $orgName): Organization
    {
        if ($user->role !== AccountType::INDEPENDENT) {
            throw new \InvalidArgumentException('Only independent users can create an organization.');
        }

        return DB::transaction(function () use ($user, $orgName) {
            // Ensure permissions exist
            Artisan::call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);

            // Create organization
            $org = Organization::create([
                'name' => $orgName,
                'slug' => Str::slug($orgName),
                'status' => OrganizationStatus::ACTIVE,
                'owner_user_id' => $user->id,
            ]);

            // Migrate user's projects to the new org
            Project::where('creator_user_id', $user->id)
                ->whereNull('organization_id')
                ->update(['organization_id' => $org->id]);

            // Upgrade user role
            $user->update([
                'role' => AccountType::ORG_ADMIN,
                'organization_id' => $org->id,
            ]);

            // Assign Spatie role + permissions
            $level = PermissionLevel::ADMIN;
            $user->syncRoles([$level->spatieRole()]);
            $user->syncPermissions($level->permissions());

            // Migrate AI config if exists (from user to org)
            $userAiConfig = $user->aiConfig;
            if ($userAiConfig) {
                $userAiConfig->update([
                    'configurable_type' => Organization::class,
                    'configurable_id' => $org->id,
                ]);
            }

            return $org;
        });
    }
}
