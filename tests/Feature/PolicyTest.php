<?php

use App\Enums\AccountType;
use App\Enums\InvitationStatus;
use App\Enums\ProjectStatus;
use App\Models\Activity;
use App\Models\Invitation;
use App\Models\Project;

beforeEach(function () {
    seedPermissions();
});

/*
|--------------------------------------------------------------------------
| ProjectPolicy
|--------------------------------------------------------------------------
*/

describe('ProjectPolicy', function () {

    test('org_admin peut voir les projets de son organisation', function () {
        $admin = createOrgAdmin();
        $admin->assignRole('ORG_ADMIN');

        $project = Project::factory()->create([
            'organization_id' => $admin->organization_id,
        ]);

        loginAs($admin);

        expect($admin->can('view', $project))->toBeTrue();
    });

    test('org_user avec role MEMBER peut voir les projets de son organisation', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        $user->assignRole('MEMBER');

        $project = Project::factory()->create([
            'organization_id' => $org->id,
        ]);

        loginAs($user);

        expect($user->can('view', $project))->toBeTrue();
    });

    test('un utilisateur ne peut pas voir les projets d une autre organisation', function () {
        $org1 = createOrg();
        $org2 = createOrg();

        $user = createOrgUser($org1);
        $user->assignRole('MEMBER');

        $project = Project::factory()->create([
            'organization_id' => $org2->id,
        ]);

        loginAs($user);

        expect($user->can('view', $project))->toBeFalse();
    });

    test('ROOT peut voir n importe quel projet', function () {
        $root = createRoot();
        $root->assignRole('IT_ADMIN');

        $org = createOrg();
        $project = Project::factory()->create([
            'organization_id' => $org->id,
        ]);

        loginAs($root);

        expect($root->can('view', $project))->toBeTrue();
    });

    test('le createur peut modifier son projet en brouillon meme sans permission edit-projects', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        $user->assignRole('MEMBER'); // MEMBER n'a pas edit-projects

        $project = Project::factory()->create([
            'organization_id' => $org->id,
            'creator_user_id' => $user->id,
            'status' => ProjectStatus::DRAFT,
        ]);

        loginAs($user);

        expect($user->can('update', $project))->toBeTrue();
    });

    test('le createur ne peut pas modifier son projet non-brouillon sans permission edit-projects', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        $user->assignRole('MEMBER');

        $project = Project::factory()->create([
            'organization_id' => $org->id,
            'creator_user_id' => $user->id,
            'status' => ProjectStatus::ACTIVE,
        ]);

        loginAs($user);

        expect($user->can('update', $project))->toBeFalse();
    });

    test('org_admin peut modifier tout projet de son organisation', function () {
        $org = createOrg();
        $admin = createOrgAdmin($org);
        $admin->assignRole('ORG_ADMIN');

        $project = Project::factory()->create([
            'organization_id' => $org->id,
            'status' => ProjectStatus::ACTIVE,
        ]);

        loginAs($admin);

        expect($admin->can('update', $project))->toBeTrue();
    });

    test('org_user sans permission edit-projects ne peut pas modifier un projet dont il n est pas createur', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        $user->assignRole('MEMBER');

        $project = Project::factory()->create([
            'organization_id' => $org->id,
            'status' => ProjectStatus::ACTIVE,
        ]);

        loginAs($user);

        expect($user->can('update', $project))->toBeFalse();
    });

    test('org_admin peut supprimer les projets de son organisation', function () {
        $org = createOrg();
        $admin = createOrgAdmin($org);
        $admin->assignRole('ORG_ADMIN');

        $project = Project::factory()->create([
            'organization_id' => $org->id,
        ]);

        loginAs($admin);

        expect($admin->can('delete', $project))->toBeTrue();
    });

    test('un utilisateur ne peut pas supprimer un projet d une autre organisation', function () {
        $org1 = createOrg();
        $org2 = createOrg();

        $admin = createOrgAdmin($org1);
        $admin->assignRole('ORG_ADMIN');

        $project = Project::factory()->create([
            'organization_id' => $org2->id,
        ]);

        loginAs($admin);

        expect($admin->can('delete', $project))->toBeFalse();
    });

    test('ROOT avec IT_ADMIN bypass toutes les policies via Gate::before', function () {
        $root = createRoot();
        $root->assignRole('IT_ADMIN');

        $project = Project::factory()->create();

        loginAs($root);

        // Gate::before retourne true pour ROOT + IT_ADMIN, bypass complet
        expect($root->can('create', Project::class))->toBeTrue();
        expect($root->can('update', $project))->toBeTrue();
        expect($root->can('delete', $project))->toBeTrue();
    });

    test('ROOT sans role IT_ADMIN ne peut pas creer ni modifier un projet', function () {
        $root = createRoot();
        // Pas de role IT_ADMIN, donc Gate::before retourne null et la policy s applique
        $root->givePermissionTo('view-projects');
        $root->givePermissionTo('create-projects');
        $root->givePermissionTo('edit-projects');

        $project = Project::factory()->create();

        loginAs($root);

        // La policy bloque explicitement ROOT pour create/update
        expect($root->can('create', Project::class))->toBeFalse();
        expect($root->can('update', $project))->toBeFalse();
    });
});

/*
|--------------------------------------------------------------------------
| ActivityPolicy
|--------------------------------------------------------------------------
*/

describe('ActivityPolicy', function () {

    test('org_admin peut creer des activites', function () {
        $admin = createOrgAdmin();
        $admin->assignRole('ORG_ADMIN');

        loginAs($admin);

        expect($admin->can('create', Activity::class))->toBeTrue();
    });

    test('org_admin peut modifier une activite de son organisation', function () {
        $org = createOrg();
        $admin = createOrgAdmin($org);
        $admin->assignRole('ORG_ADMIN');

        $activity = Activity::factory()->create([
            'organization_id' => $org->id,
        ]);

        loginAs($admin);

        expect($admin->can('update', $activity))->toBeTrue();
    });

    test('org_admin peut supprimer une activite de son organisation', function () {
        $org = createOrg();
        $admin = createOrgAdmin($org);
        $admin->assignRole('ORG_ADMIN');

        $activity = Activity::factory()->create([
            'organization_id' => $org->id,
        ]);

        loginAs($admin);

        expect($admin->can('delete', $activity))->toBeTrue();
    });

    test('un utilisateur d une autre organisation ne peut pas voir une activite', function () {
        $org1 = createOrg();
        $org2 = createOrg();

        $user = createOrgUser($org1);
        $user->assignRole('MEMBER');

        $activity = Activity::factory()->create([
            'organization_id' => $org2->id,
        ]);

        loginAs($user);

        expect($user->can('view', $activity))->toBeFalse();
    });

    test('un utilisateur d une autre organisation ne peut pas modifier une activite', function () {
        $org1 = createOrg();
        $org2 = createOrg();

        $user = createOrgUser($org1);
        $user->assignRole('ORG_ADMIN');

        $activity = Activity::factory()->create([
            'organization_id' => $org2->id,
        ]);

        loginAs($user);

        expect($user->can('update', $activity))->toBeFalse();
    });

    test('ROOT avec IT_ADMIN bypass et peut tout faire sur les activites', function () {
        $root = createRoot();
        $root->assignRole('IT_ADMIN');

        $activity = Activity::factory()->create();

        loginAs($root);

        // Gate::before retourne true pour ROOT + IT_ADMIN
        expect($root->can('view', $activity))->toBeTrue();
        expect($root->can('update', $activity))->toBeTrue();
        expect($root->can('create', Activity::class))->toBeTrue();
    });

    test('ROOT sans IT_ADMIN peut voir une activite mais pas la modifier', function () {
        $root = createRoot();
        $root->givePermissionTo('view-projects');
        $root->givePermissionTo('manage-activities');

        $activity = Activity::factory()->create();

        loginAs($root);

        // La policy autorise view pour ROOT
        expect($root->can('view', $activity))->toBeTrue();
        // La policy bloque update/create pour ROOT
        expect($root->can('update', $activity))->toBeFalse();
        expect($root->can('create', Activity::class))->toBeFalse();
    });

    test('un utilisateur avec manage-activities peut modifier une activite de son organisation', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        $user->assignRole('MANAGER');

        $activity = Activity::factory()->create([
            'organization_id' => $org->id,
        ]);

        loginAs($user);

        expect($user->can('update', $activity))->toBeTrue();
    });

    test('un utilisateur sans manage-activities ne peut pas modifier une activite', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        // Ne pas assigner de role → pas de permissions du tout
        // (MEMBER a maintenant manage-activities via PermissionLevel CONTRIBUTOR)

        $activity = Activity::factory()->create([
            'organization_id' => $org->id,
        ]);

        loginAs($user);

        expect($user->can('update', $activity))->toBeFalse();
    });
});

/*
|--------------------------------------------------------------------------
| InvitationPolicy
|--------------------------------------------------------------------------
*/

describe('InvitationPolicy', function () {

    test('org_admin peut voir les invitations', function () {
        $admin = createOrgAdmin();
        $admin->assignRole('ORG_ADMIN');

        loginAs($admin);

        expect($admin->can('viewAny', Invitation::class))->toBeTrue();
    });

    test('org_admin peut creer une invitation', function () {
        $admin = createOrgAdmin();
        $admin->assignRole('ORG_ADMIN');

        loginAs($admin);

        expect($admin->can('create', Invitation::class))->toBeTrue();
    });

    test('org_admin peut supprimer une invitation de son organisation', function () {
        $org = createOrg();
        $admin = createOrgAdmin($org);
        $admin->assignRole('ORG_ADMIN');

        $invitation = Invitation::create([
            'email' => 'test@example.com',
            'token' => 'abc123',
            'code' => 'INV-001',
            'organization_id' => $org->id,
            'invited_by' => $admin->id,
            'status' => InvitationStatus::PENDING,
            'expires_at' => now()->addDays(7),
        ]);

        loginAs($admin);

        expect($admin->can('delete', $invitation))->toBeTrue();
    });

    test('org_user sans permission ne peut pas voir les invitations', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        $user->assignRole('MEMBER'); // MEMBER n'a pas manage-invitations

        loginAs($user);

        expect($user->can('viewAny', Invitation::class))->toBeFalse();
    });

    test('org_user sans permission ne peut pas creer d invitation', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        $user->assignRole('MEMBER');

        loginAs($user);

        expect($user->can('create', Invitation::class))->toBeFalse();
    });

    test('org_user avec permission manage-invitations peut voir les invitations', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        $user->assignRole('MEMBER');
        $user->givePermissionTo('manage-invitations');

        loginAs($user);

        expect($user->can('viewAny', Invitation::class))->toBeTrue();
    });

    test('org_user avec permission invite-users peut creer une invitation', function () {
        $org = createOrg();
        $user = createOrgUser($org);
        $user->assignRole('MEMBER');
        $user->givePermissionTo('invite-users');

        loginAs($user);

        expect($user->can('create', Invitation::class))->toBeTrue();
    });

    test('ROOT peut voir les invitations', function () {
        $root = createRoot();
        $root->assignRole('IT_ADMIN');

        loginAs($root);

        expect($root->can('viewAny', Invitation::class))->toBeTrue();
    });

    test('ROOT peut supprimer une invitation', function () {
        $root = createRoot();
        $root->assignRole('IT_ADMIN');

        $org = createOrg();
        $invitation = Invitation::create([
            'email' => 'test@example.com',
            'token' => 'abc123',
            'code' => 'INV-001',
            'organization_id' => $org->id,
            'invited_by' => $root->id,
            'status' => InvitationStatus::PENDING,
            'expires_at' => now()->addDays(7),
        ]);

        loginAs($root);

        expect($root->can('delete', $invitation))->toBeTrue();
    });

    test('un utilisateur ne peut pas supprimer une invitation d une autre organisation', function () {
        $org1 = createOrg();
        $org2 = createOrg();

        $admin = createOrgAdmin($org1);
        $admin->assignRole('ORG_ADMIN');

        $invitation = Invitation::create([
            'email' => 'test@example.com',
            'token' => 'abc123',
            'code' => 'INV-001',
            'organization_id' => $org2->id,
            'invited_by' => createOrgAdmin($org2)->id,
            'status' => InvitationStatus::PENDING,
            'expires_at' => now()->addDays(7),
        ]);

        loginAs($admin);

        expect($admin->can('delete', $invitation))->toBeFalse();
    });
});
