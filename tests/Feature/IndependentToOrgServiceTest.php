<?php

use App\Enums\AccountType;
use App\Enums\PermissionLevel;
use App\Models\Organization;
use App\Models\Project;
use App\Services\IndependentToOrgService;

test('migrate creates an organization and upgrades the independent user', function () {
    seedPermissions();

    $user = createIndependent();

    $service = new IndependentToOrgService();
    $org = $service->migrate($user, 'Mon Association');

    // Organization created with correct attributes
    expect($org)->toBeInstanceOf(Organization::class);
    expect($org->name)->toBe('Mon Association');
    expect($org->slug)->toBe('mon-association');
    expect($org->owner_user_id)->toBe($user->id);

    // User upgraded
    $user->refresh();
    expect($user->role)->toBe(AccountType::ORG_ADMIN);
    expect($user->organization_id)->toBe($org->id);
});

test('migrate transfers independent user projects to the new organization', function () {
    seedPermissions();

    $user = createIndependent();
    loginAs($user);

    // Insert projects directly to avoid the boot() hook calling User::find()
    // which fails under the independent multitenantable scope
    $projectType = \App\Models\ProjectType::factory()->create();
    $project1Id = (string) \Illuminate\Support\Str::orderedUuid();
    $project2Id = (string) \Illuminate\Support\Str::orderedUuid();

    \Illuminate\Support\Facades\DB::table('projects')->insert([
        [
            'id' => $project1Id,
            'title' => 'Projet 1',
            'project_code' => 'PRJ-IND-001',
            'creator_user_id' => $user->id,
            'organization_id' => null,
            'project_type_id' => $projectType->id,
            'status' => \App\Enums\ProjectStatus::DRAFT->value,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'id' => $project2Id,
            'title' => 'Projet 2',
            'project_code' => 'PRJ-IND-002',
            'creator_user_id' => $user->id,
            'organization_id' => null,
            'project_type_id' => $projectType->id,
            'status' => \App\Enums\ProjectStatus::DRAFT->value,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    $project1 = Project::withoutGlobalScopes()->find($project1Id);
    $project2 = Project::withoutGlobalScopes()->find($project2Id);

    $service = new IndependentToOrgService();
    $org = $service->migrate($user, 'New Org');

    // Reload without scopes to check the update
    $project1 = Project::withoutGlobalScopes()->find($project1Id);
    $project2 = Project::withoutGlobalScopes()->find($project2Id);

    expect($project1->organization_id)->toBe($org->id);
    expect($project2->organization_id)->toBe($org->id);
});

test('migrate assigns Spatie ADMIN role and permissions', function () {
    seedPermissions();

    $user = createIndependent();

    $service = new IndependentToOrgService();
    $service->migrate($user, 'Test Org');

    $user->refresh();

    expect($user->hasRole(PermissionLevel::ADMIN->spatieRole()))->toBeTrue();

    $expectedPermissions = PermissionLevel::ADMIN->permissions();
    foreach ($expectedPermissions as $permission) {
        expect($user->hasPermissionTo($permission))->toBeTrue();
    }
});

test('migrate throws exception when user is not independent', function () {
    $user = createOrgAdmin();

    $service = new IndependentToOrgService();

    expect(fn () => $service->migrate($user, 'Org'))
        ->toThrow(\InvalidArgumentException::class, 'Only independent users can create an organization.');
});

test('migrate works even when user has no projects', function () {
    seedPermissions();

    $user = createIndependent();

    $service = new IndependentToOrgService();
    $org = $service->migrate($user, 'Empty Org');

    expect($org)->toBeInstanceOf(Organization::class);
    expect($org->name)->toBe('Empty Org');

    $user->refresh();
    expect($user->role)->toBe(AccountType::ORG_ADMIN);
});
