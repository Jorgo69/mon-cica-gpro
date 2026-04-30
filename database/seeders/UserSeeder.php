<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Organization;
use App\Enums\AccountType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Seed des comptes de test couvrant tous les types de comptes et scenarios.
     *
     * Mot de passe universel : password
     *
     * COMPTES :
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
        // ══════════════════════════════════════════════════════════
        // ORGANISATION 1 : Projexia International (client test)
        // ══════════════════════════════════════════════════════════
        $org1 = Organization::firstOrCreate(
            ['slug' => 'projexia-international'],
            [
                'name' => 'Projexia International',
                'status' => \App\Enums\OrganizationStatus::ACTIVE,
            ]
        );

        // ══════════════════════════════════════════════════════════
        // ORGANISATION 2 : ONG Espoir (pour tester isolation cross-org)
        // ══════════════════════════════════════════════════════════
        $org2 = Organization::firstOrCreate(
            ['slug' => 'ong-espoir'],
            [
                'name' => 'ONG Espoir',
                'status' => \App\Enums\OrganizationStatus::ACTIVE,
            ]
        );

        // ══════════════════════════════════════════════════════════
        // ROOT : Super Admin plateforme (pas d'org)
        // ══════════════════════════════════════════════════════════
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
                'pays' => 'Bénin',
                'ville' => 'Cotonou',
            ]
        );
        $root->syncRoles(['IT_ADMIN']);

        // ══════════════════════════════════════════════════════════
        // PROJEXIA : org_admin + manager + membre
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
                'pays' => 'Bénin',
                'ville' => 'Cotonou',
            ]
        );
        $org1Admin->syncRoles(['ORG_ADMIN']);

        $org1Manager = User::firstOrCreate(
            ['email' => 'manager@projexia.org'],
            [
                'name' => 'Sophie Koumé',
                'password' => 'password',
                'email_verified_at' => now(),
                'organization_id' => $org1->id,
                'role' => AccountType::ORG_USER,
                'sexe' => 'Femme',
                'department' => 'Gestion de Projets',
                'telephone' => '+229 96 11 22 33',
                'pays' => 'Bénin',
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
                'department' => 'Terrain & Opérations',
                'telephone' => '+229 96 44 55 66',
                'pays' => 'Bénin',
                'ville' => 'Parakou',
            ]
        );
        $org1Member->syncRoles(['MEMBER']);

        // ══════════════════════════════════════════════════════════
        // ONG ESPOIR : org_admin + membre (isolation cross-org)
        // ══════════════════════════════════════════════════════════
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
                'pays' => 'Bénin',
                'ville' => 'Porto-Novo',
            ]
        );
        $org2Admin->syncRoles(['ORG_ADMIN']);

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
                'pays' => 'Bénin',
                'ville' => 'Porto-Novo',
            ]
        );
        $org2Member->syncRoles(['MEMBER']);

        // ══════════════════════════════════════════════════════════
        // INDEPENDENT : utilisateur solo sans organisation
        // ══════════════════════════════════════════════════════════
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
                'pays' => 'Bénin',
                'ville' => 'Cotonou',
            ]
        );
        $independent->syncRoles(['MEMBER']);
    }
}
