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

            // 1. Créer l'organisation
            $organization = Organization::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'status' => 'trial',
            ]);

            Log::info('[Action] CreateOrganization - Organisation créée', ['id' => $organization->id]);

            // 2. Rattacher l'utilisateur à l'organisation
            $user->update([
                'organization_id' => $organization->id,
                'role' => 'admin',
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

            Log::info('[Action] CreateOrganization - Assignation du rôle', ['role_id' => $role->id]);
            $user->assignRole($role);
            
            // On repasse sur le contexte de l'organisation pour la suite
            setPermissionsTeamId($organization->id);

            Log::info('[Action] CreateOrganization - Rôle assigné avec succès');

            return $organization;
        });
    }
}
