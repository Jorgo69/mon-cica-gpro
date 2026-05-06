<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Enums\AccountType;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Seed des comptes de test adaptes au mode (selfhosted ou saas).
     *
     * Mot de passe universel : password
     *
     * SELFHOSTED (pas de ROOT, une seule org, le premier admin est owner) :
     * ┌──────────────────────────┬─────────────┬───────────────────────┬──────────────┐
     * │ Email                    │ Type        │ Organisation          │ Role Spatie  │
     * ├──────────────────────────┼─────────────┼───────────────────────┼──────────────┤
     * │ admin@projexia.org       │ ORG_ADMIN   │ Projexia International│ ORG_ADMIN    │
     * │ manager@projexia.org     │ ORG_USER    │ Projexia International│ MANAGER      │
     * │ membre@projexia.org      │ ORG_USER    │ Projexia International│ MEMBER       │
     * └──────────────────────────┴─────────────┴───────────────────────┴──────────────┘
     *
     * SAAS (ROOT + 2 orgs + independent) :
     * ┌──────────────────────────┬─────────────┬───────────────────────┬──────────────┐
     * │ Email                    │ Type        │ Organisation          │ Role Spatie  │
     * ├──────────────────────────┼─────────────┼───────────────────────┼──────────────┤
     * │ root@cica-gpro.com       │ ROOT        │ Aucune (supervise)    │ IT_ADMIN     │
     * │ admin@projexia.org       │ ORG_ADMIN   │ Projexia International│ ORG_ADMIN    │
     * │ manager@projexia.org     │ ORG_USER    │ Projexia International│ MANAGER      │
     * │ membre@projexia.org      │ ORG_USER    │ Projexia International│ MEMBER       │
     * │ admin@ong-espoir.org     │ ORG_ADMIN   │ ONG Espoir            │ ORG_ADMIN    │
     * │ membre@ong-espoir.org    │ ORG_USER    │ ONG Espoir            │ MEMBER       │
     * │ solo@independant.com     │ INDEPENDENT │ Aucune (solo)         │ MEMBER       │
     * └──────────────────────────┴─────────────┴───────────────────────┴──────────────┘
     */
    public function run(): void
    {
        $isSaas = isSaas();

        // ══════════════════════════════════════════════════════════
        // ORGANISATION 1 : Projexia International
        // ══════════════════════════════════════════════════════════
        $org1 = Organization::firstOrCreate(
            ['slug' => 'projexia-international'],
            [
                'name' => 'Projexia International',
                'status' => \App\Enums\OrganizationStatus::ACTIVE,
            ]
        );

        // ══════════════════════════════════════════════════════════
        // ROOT : uniquement en mode SaaS
        // ══════════════════════════════════════════════════════════
        if ($isSaas) {
            setPermissionsTeamId(null);

            $root = User::firstOrCreate(
                ['email' => 'root@cica-gpro.com'],
                [
                    'name' => 'Root Admin',
                    'password' => 'password',
                    'email_verified_at' => now(),
                    'organization_id' => null,
                    'role' => AccountType::ROOT,
                    'sexe' => 'Homme',
                    'department' => 'Plateforme',
                    'telephone' => '+229 97 00 00 00',
                    'pays' => 'Benin',
                    'ville' => 'Cotonou',
                ]
            );
            $root->syncRoles(['IT_ADMIN']);
        }

        // ══════════════════════════════════════════════════════════
        // PROJEXIA : org_admin (owner) + manager + membre
        // ══════════════════════════════════════════════════════════
        setPermissionsTeamId($org1->id);

        $org1Admin = User::firstOrCreate(
            ['email' => 'admin@projexia.org'],
            [
                'name' => 'Alice Dossou',
                'password' => 'password',
                'email_verified_at' => now(),
                'organization_id' => $org1->id,
                'role' => AccountType::ORG_ADMIN,
                'sexe' => 'Femme',
                'department' => 'Direction',
                'telephone' => '+229 97 11 22 33',
                'pays' => 'Benin',
                'ville' => 'Cotonou',
            ]
        );
        $org1Admin->syncRoles(['ORG_ADMIN']);

        // Le premier admin est proprietaire de l'org
        if (!$org1->owner_user_id) {
            $org1->update(['owner_user_id' => $org1Admin->id]);
        }

        $org1Manager = User::firstOrCreate(
            ['email' => 'manager@projexia.org'],
            [
                'name' => 'Sophie Koume',
                'password' => 'password',
                'email_verified_at' => now(),
                'organization_id' => $org1->id,
                'role' => AccountType::ORG_USER,
                'sexe' => 'Femme',
                'department' => 'Gestion de Projets',
                'telephone' => '+229 96 11 22 33',
                'pays' => 'Benin',
                'ville' => 'Abomey-Calavi',
            ]
        );
        $org1Manager->syncRoles(['MANAGER']);

        $org1Member = User::firstOrCreate(
            ['email' => 'membre@projexia.org'],
            [
                'name' => 'Idriss Gnonlon',
                'password' => 'password',
                'email_verified_at' => now(),
                'organization_id' => $org1->id,
                'role' => AccountType::ORG_USER,
                'sexe' => 'Homme',
                'department' => 'Terrain & Operations',
                'telephone' => '+229 96 44 55 66',
                'pays' => 'Benin',
                'ville' => 'Parakou',
            ]
        );
        $org1Member->syncRoles(['MEMBER']);

        // ══════════════════════════════════════════════════════════
        // MODE SAAS : ONG Espoir (isolation cross-org) + independent
        // ══════════════════════════════════════════════════════════
        if ($isSaas) {
            $org2 = Organization::firstOrCreate(
                ['slug' => 'ong-espoir'],
                [
                    'name' => 'ONG Espoir',
                    'status' => \App\Enums\OrganizationStatus::ACTIVE,
                ]
            );

            setPermissionsTeamId($org2->id);

            $org2Admin = User::firstOrCreate(
                ['email' => 'admin@ong-espoir.org'],
                [
                    'name' => 'Marie Adjo',
                    'password' => 'password',
                    'email_verified_at' => now(),
                    'organization_id' => $org2->id,
                    'role' => AccountType::ORG_ADMIN,
                    'sexe' => 'Femme',
                    'department' => 'Direction',
                    'telephone' => '+229 96 70 80 90',
                    'pays' => 'Benin',
                    'ville' => 'Porto-Novo',
                ]
            );
            $org2Admin->syncRoles(['ORG_ADMIN']);
            $org2->update(['owner_user_id' => $org2Admin->id]);

            $org2Member = User::firstOrCreate(
                ['email' => 'membre@ong-espoir.org'],
                [
                    'name' => 'Paul Koffi',
                    'password' => 'password',
                    'email_verified_at' => now(),
                    'organization_id' => $org2->id,
                    'role' => AccountType::ORG_USER,
                    'sexe' => 'Homme',
                    'department' => 'Programmes',
                    'telephone' => '+229 96 33 44 55',
                    'pays' => 'Benin',
                    'ville' => 'Porto-Novo',
                ]
            );
            $org2Member->syncRoles(['MEMBER']);

            // INDEPENDENT : utilisateur solo sans organisation
            setPermissionsTeamId(null);

            $independent = User::firstOrCreate(
                ['email' => 'solo@independant.com'],
                [
                    'name' => 'Marc Consultant',
                    'password' => 'password',
                    'email_verified_at' => now(),
                    'organization_id' => null,
                    'role' => AccountType::INDEPENDENT,
                    'is_independent' => true,
                    'sexe' => 'Homme',
                    'department' => 'Conseil',
                    'telephone' => '+229 96 99 88 77',
                    'pays' => 'Benin',
                    'ville' => 'Cotonou',
                ]
            );
            $independent->syncRoles(['MEMBER']);
        }
    }
}
