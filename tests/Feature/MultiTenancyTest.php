<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Organization;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('scoped projects: user can only see projects of their organization', function () {
    // 1. Créer deux organisations
    $org1 = Organization::factory()->create();
    $org2 = Organization::factory()->create();

    // 2. Créer des utilisateurs pour chaque organisation
    $user1 = User::factory()->create(['organization_id' => $org1->id]);
    $user2 = User::factory()->create(['organization_id' => $org2->id]);

    // 3. Créer des projets pour chaque organisation
    Project::factory()->create(['organization_id' => $org1->id, 'title' => 'Project Org 1']);
    Project::factory()->create(['organization_id' => $org2->id, 'title' => 'Project Org 2']);

    // 4. Agir en tant qu'utilisateur 1
    $this->actingAs($user1);
    expect(Project::count())->toBe(1);
    expect(Project::first()->title)->toBe('Project Org 1');

    // 5. Agir en tant qu'utilisateur 2
    $this->actingAs($user2);
    expect(Project::count())->toBe(1);
    expect(Project::first()->title)->toBe('Project Org 2');
});

test('auto-assign organization_id: projects get current user organization on creation', function () {
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);

    $this->actingAs($user);

    $project = Project::create([
        'title' => 'New Isolated Project',
        'creator_user_id' => $user->id,
        'project_type_id' => \App\Models\ProjectType::factory()->create()->id,
        'project_code' => 'TEST-001',
    ]);

    expect($project->organization_id)->toBe($organization->id);
});

test('scoped activities: activities are also isolated via organization_id', function () {
    $org1 = Organization::factory()->create();
    $org2 = Organization::factory()->create();

    $user1 = User::factory()->create(['organization_id' => $org1->id]);
    
    // Créer une activité pour org 2
    Activity::factory()->create(['organization_id' => $org2->id, 'description' => 'Secret activity']);

    $this->actingAs($user1);
    
    // L'agent ne doit voir AUCUNE activité
    expect(Activity::count())->toBe(0);
});

test('admin can see all projects regardless of organization', function () {
    $org1 = Organization::factory()->create();
    $admin = User::factory()->create([
        'role' => \App\Enums\AccountType::ADMIN,
        'organization_id' => null
    ]);

    Project::factory()->create(['organization_id' => $org1->id]);

    $this->actingAs($admin);
    expect(Project::count())->toBe(1);
});
