<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateOrganizationAction
{
    /**
     * Create an organization and assign the user as ORG_ADMIN.
     * 
     * Toute l'opération est atomique : si une étape échoue,
     * tout est annulé (rollback) pour éviter les données orphelines.
     *
     * @throws \Throwable
     */
    public function execute(User $user, string $name): Organization
    {
        return DB::transaction(function () use ($user, $name) {
            Log::info('[Action] CreateOrganization - Début transaction');

            // 1. Créer l'organisation avec l'utilisateur comme owner
            $organization = Organization::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'status' => \App\Enums\OrganizationStatus::TRIAL,
                'owner_user_id' => $user->id,
            ]);

            Log::info('[Action] CreateOrganization - Organisation créée', ['id' => $organization->id]);

            // 2. Rattacher l'utilisateur à l'organisation
            $user->update([
                'organization_id' => $organization->id,
                'role' => \App\Enums\AccountType::ORG_ADMIN,
            ]);

            Log::info('[Action] CreateOrganization - Utilisateur rattaché');

            // 3. Assigner le rôle Spatie ORG_ADMIN
            // On force le vidage du cache par précaution
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            setPermissionsTeamId(null); 
            
            Log::info('[Action] CreateOrganization - Récupération manuelle du rôle ORG_ADMIN');
            
            // On récupère le rôle via le modèle local pour être sûr du guard et de l'UUID
            $role = \App\Models\Role::where('name', 'ORG_ADMIN')
                ->where('guard_name', 'web')
                ->whereNull('organization_id')
                ->first();

            if (!$role) {
                Log::error('[Action] CreateOrganization - Role ORG_ADMIN introuvable');
                throw new \Exception("Le rôle de base 'ORG_ADMIN' est introuvable. Veuillez vérifier vos seeders.");
            }

            // S'assurer que le rôle a les permissions attendues
            $expectedPermissions = [
                'manage-organization', 'manage-users', 'manage-roles',
                'view-projects', 'create-projects', 'edit-projects', 'delete-projects', 'validate-projects',
                'manage-activities', 'track-progress', 'view-budgets', 'manage-budgets',
                'invite-users', 'manage-invitations',
            ];
            foreach ($expectedPermissions as $perm) {
                \App\Models\Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            }
            if ($role->permissions()->count() === 0) {
                $role->syncPermissions($expectedPermissions);
                Log::info('[Action] CreateOrganization - Permissions re-synchronisées sur ORG_ADMIN');
            }

            Log::info('[Action] CreateOrganization - Assignation du rôle', ['role_id' => $role->id]);
            $user->assignRole($role);

            // On repasse sur le contexte de l'organisation pour la suite
            setPermissionsTeamId($organization->id);

            // Forcer le rechargement des permissions en cache
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            Log::info('[Action] CreateOrganization - Rôle assigné avec succès');

            return $organization;
        });
    }
}
