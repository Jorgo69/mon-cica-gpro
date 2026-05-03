<?php

use App\Enums\AccountType;
use App\Models\User;
use App\Models\Project;
use App\Models\Organization;
use App\Models\Activity;

test('scoped projects: user can only see projects of their organization', function () {
    $org1 = createOrg();
    $org2 = createOrg();

    $user1 = createOrgUser($org1);
    $user2 = createOrgUser($org2);

    Project::factory()->create(['organization_id' => $org1->id, 'title' => 'Project Org 1']);
    Project::factory()->create(['organization_id' => $org2->id, 'title' => 'Project Org 2']);

    loginAs($user1);
    expect(Project::count())->toBe(1);
    expect(Project::first()->title)->toBe('Project Org 1');

    loginAs($user2);
    expect(Project::count())->toBe(1);
    expect(Project::first()->title)->toBe('Project Org 2');
});

test('auto-assign organization_id: projects get current user organization on creation', function () {
    $org = createOrg();
    $user = createOrgUser($org);

    loginAs($user);

    $project = Project::create([
        'title' => 'New Isolated Project',
        'creator_user_id' => $user->id,
        'project_type_id' => \App\Models\ProjectType::factory()->create()->id,
        'project_code' => 'TEST-001',
        'status' => \App\Enums\ProjectStatus::DRAFT,
    ]);

    expect($project->organization_id)->toBe($org->id);
});

test('scoped activities: activities are isolated via organization_id', function () {
    $org1 = createOrg();
    $org2 = createOrg();

    $user1 = createOrgUser($org1);

    Activity::factory()->create(['organization_id' => $org2->id, 'description' => 'Secret activity']);

    loginAs($user1);

    expect(Activity::count())->toBe(0);
});

test('ROOT can see all projects regardless of organization', function () {
    $org1 = createOrg();
    $root = createRoot();

    Project::factory()->create(['organization_id' => $org1->id]);

    loginAs($root);
    expect(Project::count())->toBe(1);
});
