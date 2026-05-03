<?php

use App\Enums\ProjectStatus;
use App\Models\Organization;
use App\Models\Project;
use App\Models\ProjectType;

test('authenticated user can see project list page', function () {
    $user = createOrgAdmin();

    $this->actingAs($user)
        ->get(route('project.list'))
        ->assertStatus(200);
});

test('authenticated user can create a project via Eloquent', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    $projectType = ProjectType::factory()->create();

    loginAs($user);

    $project = Project::create([
        'title' => 'Mon Nouveau Projet',
        'project_code' => 'PRJ-TEST-001',
        'status' => ProjectStatus::DRAFT,
        'project_type_id' => $projectType->id,
        'creator_user_id' => $user->id,
    ]);

    expect($project)->toBeInstanceOf(Project::class);
    expect($project->title)->toBe('Mon Nouveau Projet');
    expect($project->status)->toBe(ProjectStatus::DRAFT);
    $this->assertDatabaseHas('projects', ['project_code' => 'PRJ-TEST-001']);
});

test('project is auto-assigned to user organization', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    $projectType = ProjectType::factory()->create();

    loginAs($user);

    $project = Project::create([
        'title' => 'Auto Org Project',
        'project_code' => 'PRJ-AUTO-001',
        'status' => ProjectStatus::DRAFT,
        'project_type_id' => $projectType->id,
        'creator_user_id' => $user->id,
    ]);

    expect($project->organization_id)->toBe($org->id);
});

test('user cannot see projects from other organization', function () {
    $org1 = createOrg();
    $org2 = createOrg();

    $user1 = createOrgUser($org1);

    Project::factory()->create(['organization_id' => $org1->id, 'title' => 'Visible']);
    Project::factory()->create(['organization_id' => $org2->id, 'title' => 'Hidden']);

    loginAs($user1);

    $projects = Project::all();
    expect($projects)->toHaveCount(1);
    expect($projects->first()->title)->toBe('Visible');
});

test('ROOT can see all projects', function () {
    $org1 = createOrg();
    $org2 = createOrg();

    Project::factory()->create(['organization_id' => $org1->id]);
    Project::factory()->create(['organization_id' => $org2->id]);

    $root = createRoot();
    loginAs($root);

    expect(Project::count())->toBe(2);
});

test('project show page works for owner', function () {
    seedPermissions();

    $org = createOrg();
    $user = createOrgAdmin($org);

    // Spatie teams mode: set team context to user's org before assigning role
    setPermissionsTeamId($org->id);
    $user->assignRole('ORG_ADMIN');
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->get(route('project.show', ['projectId' => $project->id]))
        ->assertStatus(200);
});
