<?php

use App\Enums\AccountType;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Notifications\OrgDeletionScheduledNotification;
use App\Services\GdprDeleteService;
use App\Services\GdprExportService;
use Illuminate\Support\Facades\Notification;

function seedPlans(): void
{
    (new \Database\Seeders\PlanSeeder())->run();
}

/*
|--------------------------------------------------------------------------
| GdprExportService — Export personnel
|--------------------------------------------------------------------------
*/

test('export personnel contient les cles attendues', function () {
    $user = createUser(['name' => 'Alice Dupont', 'email' => 'alice@test.com']);

    $data = GdprExportService::exportPersonal($user);

    expect($data)->toHaveKeys([
        'account',
        'projects_created',
        'activities_assigned',
        'comments',
        'social_accounts',
        'notifications',
        'exported_at',
    ]);
    expect($data['account']['name'])->toBe('Alice Dupont');
    expect($data['account']['email'])->toBe('alice@test.com');
});

test('export personnel inclut les projets crees par le user', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
        'title' => 'Projet GDPR Test',
    ]);

    $data = GdprExportService::exportPersonal($user);

    expect($data['projects_created'])->toHaveCount(1);
    expect($data['projects_created'][0]['title'])->toBe('Projet GDPR Test');
});

test('export personnel inclut les activites assignees', function () {
    $org = createOrg();
    $user = createOrgUser($org);

    $project = Project::factory()->create(['organization_id' => $org->id]);
    $lf = \App\Models\LogicalFramework::factory()->create([
        'project_id' => $project->id,
        'organization_id' => $org->id,
    ]);
    $so = \App\Models\SpecificObjective::factory()->create([
        'logical_framework_id' => $lf->id,
        'organization_id' => $org->id,
    ]);
    $result = \App\Models\Result::factory()->create([
        'specific_objective_id' => $so->id,
        'organization_id' => $org->id,
    ]);
    Activity::factory()->create([
        'organization_id' => $org->id,
        'result_id' => $result->id,
        'responsible_user_id' => $user->id,
        'description' => 'Activite assignee',
    ]);

    $data = GdprExportService::exportPersonal($user);

    expect($data['activities_assigned'])->toHaveCount(1);
    expect($data['activities_assigned'][0]['description'])->toBe('Activite assignee');
});

test('export legacy method delegue a exportPersonal', function () {
    $user = createUser();

    $this->freezeTime();

    expect(GdprExportService::export($user))
        ->toEqual(GdprExportService::exportPersonal($user));
});

/*
|--------------------------------------------------------------------------
| GdprExportService — Export organisation
|--------------------------------------------------------------------------
*/

test('export org contient les cles attendues et les membres', function () {
    seedPlans();

    $org = createOrg();
    $owner = createOrgAdmin($org);
    $org->update(['owner_user_id' => $owner->id]);
    $member = createOrgUser($org);

    $data = GdprExportService::exportOrganization($org);

    expect($data)->toHaveKeys([
        'organization',
        'members',
        'projects',
        'budgets',
        'expenses',
        'invitations',
        'exported_at',
    ]);
    expect($data['organization']['name'])->toBe($org->name);
    expect($data['members'])->toHaveCount(2);
});

test('export org inclut les projets de l organisation', function () {
    seedPlans();

    $org = createOrg();
    $admin = createOrgAdmin($org);
    $org->update(['owner_user_id' => $admin->id]);

    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $admin->id,
        'title' => 'Projet Org Export',
    ]);

    $data = GdprExportService::exportOrganization($org);

    expect($data['projects'])->toHaveCount(1);
    expect($data['projects'][0]['title'])->toBe('Projet Org Export');
});

/*
|--------------------------------------------------------------------------
| GdprDeleteService — Anonymisation
|--------------------------------------------------------------------------
*/

test('anonymisation efface les donnees personnelles et soft-delete le user', function () {
    $user = createUser([
        'name' => 'Jean Martin',
        'email' => 'jean@test.com',
        'telephone' => '+33612345678',
        'sexe' => 'M',
        'pays' => 'France',
        'ville' => 'Paris',
        'role' => AccountType::INDEPENDENT,
        'organization_id' => null,
    ]);

    GdprDeleteService::anonymizeAndDelete($user);

    $deleted = User::withTrashed()->find($user->id);

    expect($deleted->name)->toBe(__('settings.delete.deleted_user'));
    expect($deleted->email)->toContain('@deleted.local');
    expect($deleted->email)->toStartWith('deleted-');
    expect($deleted->telephone)->toBeNull();
    expect($deleted->sexe)->toBeNull();
    expect($deleted->pays)->toBeNull();
    expect($deleted->ville)->toBeNull();
    expect($deleted->role)->toBeNull();
    expect($deleted->deleted_at)->not->toBeNull();
});

test('anonymisation desassigne les activites du user', function () {
    $org = createOrg();
    $user = createOrgUser($org);

    $project = Project::factory()->create(['organization_id' => $org->id]);
    $lf = \App\Models\LogicalFramework::factory()->create([
        'project_id' => $project->id,
        'organization_id' => $org->id,
    ]);
    $so = \App\Models\SpecificObjective::factory()->create([
        'logical_framework_id' => $lf->id,
        'organization_id' => $org->id,
    ]);
    $result = \App\Models\Result::factory()->create([
        'specific_objective_id' => $so->id,
        'organization_id' => $org->id,
    ]);
    $activity = Activity::factory()->create([
        'organization_id' => $org->id,
        'result_id' => $result->id,
        'responsible_user_id' => $user->id,
    ]);

    GdprDeleteService::anonymizeAndDelete($user);

    expect($activity->fresh()->responsible_user_id)->toBeNull();
});

test('anonymisation supprime les commentaires du user', function () {
    $org = createOrg();
    $user = createOrgUser($org);
    $project = Project::factory()->create(['organization_id' => $org->id]);

    Comment::create([
        'user_id' => $user->id,
        'organization_id' => $org->id,
        'commentable_type' => Project::class,
        'commentable_id' => $project->id,
        'body' => 'Mon commentaire GDPR',
    ]);

    expect(Comment::where('user_id', $user->id)->count())->toBe(1);

    GdprDeleteService::anonymizeAndDelete($user);

    // Comment utilise SoftDeletes, donc le delete() du service fait un soft-delete
    expect(Comment::withTrashed()->where('user_id', $user->id)->count())->toBe(1);
    expect(Comment::where('user_id', $user->id)->count())->toBe(0);
});

/*
|--------------------------------------------------------------------------
| GdprDeleteService — Protection owner
|--------------------------------------------------------------------------
*/

test('owner unique ne peut pas etre anonymise', function () {
    $org = createOrg();
    $owner = createOrgAdmin($org);
    $org->update(['owner_user_id' => $owner->id]);

    expect(fn () => GdprDeleteService::anonymizeAndDelete($owner))
        ->toThrow(\InvalidArgumentException::class);
});

test('owner peut etre anonymise si un autre admin existe', function () {
    $org = createOrg();
    $owner = createOrgAdmin($org);
    $org->update(['owner_user_id' => $owner->id]);

    // Creer un second admin
    createOrgAdmin($org);

    GdprDeleteService::anonymizeAndDelete($owner);

    expect(User::withTrashed()->find($owner->id)->deleted_at)->not->toBeNull();
});

/*
|--------------------------------------------------------------------------
| GdprDeleteService — Suppression org planifiee
|--------------------------------------------------------------------------
*/

test('suppression org planifiee set les meta et notifie les membres', function () {
    Notification::fake();

    $org = createOrg();
    $owner = createOrgAdmin($org);
    $org->update(['owner_user_id' => $owner->id]);
    $member = createOrgUser($org);

    $org->refresh();
    $org->load('users');

    GdprDeleteService::scheduleOrgDeletion($org, $owner);

    $org->refresh();

    expect($org->getMeta('deletion_scheduled_at'))->not->toBeNull();
    expect($org->getMeta('deletion_requested_by'))->toBe($owner->id);

    Notification::assertSentTo($owner, OrgDeletionScheduledNotification::class);
    Notification::assertSentTo($member, OrgDeletionScheduledNotification::class);
});

test('seul l owner peut planifier la suppression org', function () {
    $org = createOrg();
    $owner = createOrgAdmin($org);
    $org->update(['owner_user_id' => $owner->id]);

    $otherUser = createOrgUser($org);

    expect(fn () => GdprDeleteService::scheduleOrgDeletion($org, $otherUser))
        ->toThrow(\InvalidArgumentException::class);
});

test('annulation suppression org efface les meta', function () {
    $org = createOrg();
    $org->setMeta('deletion_scheduled_at', now()->toISOString());
    $org->setMeta('deletion_requested_by', 'some-user-id');
    $org->save();

    GdprDeleteService::cancelOrgDeletion($org);

    $org->refresh();

    expect($org->getMeta('deletion_scheduled_at'))->toBeNull();
    expect($org->getMeta('deletion_requested_by'))->toBeNull();
});

test('execution suppression org soft-delete l org et detache les membres', function () {
    $org = createOrg();
    $owner = createOrgAdmin($org);
    $org->update(['owner_user_id' => $owner->id]);
    $member = createOrgUser($org);

    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $owner->id,
    ]);

    GdprDeleteService::executeOrgDeletion($org);

    // L'org est soft-deleted
    expect(Organization::withTrashed()->find($org->id)->deleted_at)->not->toBeNull();

    // Les membres sont detaches
    expect($owner->fresh()->organization_id)->toBeNull();
    expect($member->fresh()->organization_id)->toBeNull();

    // Les projets sont soft-deleted (plus visibles normalement, mais presents avec withTrashed)
    expect(Project::where('organization_id', $org->id)->count())->toBe(0);
    expect(Project::withTrashed()->where('organization_id', $org->id)->count())->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Route HTTP — /profile/export-data
|--------------------------------------------------------------------------
*/

test('route export-data retourne JSON pour un user authentifie', function () {
    $user = createUser();

    $response = $this->actingAs($user)->get('/profile/export-data');

    $response->assertOk();
    $response->assertJsonStructure([
        'account' => ['name', 'email'],
        'projects_created',
        'activities_assigned',
        'comments',
        'exported_at',
    ]);
});

test('route export-data redirige si non authentifie', function () {
    $response = $this->get('/profile/export-data');

    $response->assertRedirect('/login');
});
