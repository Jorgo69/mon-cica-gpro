<?php

use App\Enums\ActivityStatus;
use App\Enums\ProjectStatus;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Result;

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function apiToken(\App\Models\User $user): string
{
    return $user->createToken('test')->plainTextToken;
}

function apiHeaders(\App\Models\User $user): array
{
    return [
        'Authorization' => 'Bearer ' . apiToken($user),
        'Accept' => 'application/json',
    ];
}

function jsonHeaders(): array
{
    return [
        'Accept' => 'application/json',
    ];
}

/*
|--------------------------------------------------------------------------
| Authentication - 401 sans token
|--------------------------------------------------------------------------
*/

test('GET /api/v1/me retourne 401 sans token', function () {
    $this->getJson('/api/v1/me', jsonHeaders())
        ->assertStatus(401);
});

test('GET /api/v1/projects retourne 401 sans token', function () {
    $this->getJson('/api/v1/projects', jsonHeaders())
        ->assertStatus(401);
});

test('POST /api/v1/projects retourne 401 sans token', function () {
    $this->postJson('/api/v1/projects', ['title' => 'Test'], jsonHeaders())
        ->assertStatus(401);
});

test('GET /api/v1/activities retourne 401 sans token', function () {
    $this->getJson('/api/v1/activities', jsonHeaders())
        ->assertStatus(401);
});

test('GET /api/v1/stats retourne 401 sans token', function () {
    $this->getJson('/api/v1/stats', jsonHeaders())
        ->assertStatus(401);
});

test('GET /api/v1/notifications retourne 401 sans token', function () {
    $this->getJson('/api/v1/notifications', jsonHeaders())
        ->assertStatus(401);
});

/*
|--------------------------------------------------------------------------
| GET /api/v1/me
|--------------------------------------------------------------------------
*/

test('GET /api/v1/me retourne les infos de l utilisateur authentifie', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    $this->getJson('/api/v1/me', apiHeaders($user))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ])
        ->assertJsonStructure(['id', 'name', 'email', 'role', 'organization', 'permissions']);
});

test('GET /api/v1/me inclut l organisation de l utilisateur', function () {
    $org = createOrg(['name' => 'CICA Test Org']);
    $user = createOrgAdmin($org);

    $response = $this->getJson('/api/v1/me', apiHeaders($user))
        ->assertOk();

    expect($response->json('organization.name'))->toBe('CICA Test Org');
});

test('GET /api/v1/me retourne null pour organization quand user independant', function () {
    $user = createIndependent();

    $response = $this->getJson('/api/v1/me', apiHeaders($user))
        ->assertOk();

    expect($response->json('organization'))->toBeNull();
});

/*
|--------------------------------------------------------------------------
| GET /api/v1/projects
|--------------------------------------------------------------------------
*/

test('GET /api/v1/projects retourne une liste paginee', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    Project::factory()->count(3)->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    loginAs($user);

    $response = $this->getJson('/api/v1/projects', apiHeaders($user))
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);

    expect($response->json('data'))->toHaveCount(3);
});

test('GET /api/v1/projects ne retourne pas les projets d une autre org', function () {
    $org1 = createOrg();
    $org2 = createOrg();
    $user = createOrgAdmin($org1);

    Project::factory()->create(['organization_id' => $org1->id, 'creator_user_id' => $user->id]);
    Project::factory()->create(['organization_id' => $org2->id]);

    loginAs($user);

    $response = $this->getJson('/api/v1/projects', apiHeaders($user))
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

test('GET /api/v1/projects supporte le filtre par statut', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
        'status' => ProjectStatus::DRAFT,
    ]);
    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
        'status' => ProjectStatus::ACTIVE,
    ]);

    loginAs($user);

    $response = $this->getJson('/api/v1/projects?status=' . ProjectStatus::DRAFT->value, apiHeaders($user))
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

test('GET /api/v1/projects supporte per_page', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    Project::factory()->count(5)->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    loginAs($user);

    $response = $this->getJson('/api/v1/projects?per_page=2', apiHeaders($user))
        ->assertOk();

    expect($response->json('data'))->toHaveCount(2);
    expect($response->json('meta.per_page'))->toBe(2);
});

/*
|--------------------------------------------------------------------------
| POST /api/v1/projects
|--------------------------------------------------------------------------
*/

test('POST /api/v1/projects cree un projet', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    loginAs($user);

    $response = $this->postJson('/api/v1/projects', [
        'title' => 'Projet API Test',
        'description' => 'Description du projet',
    ], apiHeaders($user))
        ->assertStatus(201);

    expect($response->json('data.title'))->toBe('Projet API Test');
    $this->assertDatabaseHas('projects', ['title' => 'Projet API Test']);
});

test('POST /api/v1/projects echoue sans titre', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    loginAs($user);

    $this->postJson('/api/v1/projects', [
        'description' => 'Sans titre',
    ], apiHeaders($user))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('POST /api/v1/projects echoue si end_date avant start_date', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    loginAs($user);

    $this->postJson('/api/v1/projects', [
        'title' => 'Dates invalides',
        'start_date' => '2026-06-01',
        'end_date' => '2026-01-01',
    ], apiHeaders($user))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['end_date']);
});

/*
|--------------------------------------------------------------------------
| GET /api/v1/projects/{id}
|--------------------------------------------------------------------------
*/

test('GET /api/v1/projects/{id} retourne le detail d un projet', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
        'title' => 'Projet Detail',
    ]);

    loginAs($user);

    $this->getJson("/api/v1/projects/{$project->id}", apiHeaders($user))
        ->assertOk()
        ->assertJsonFragment(['title' => 'Projet Detail']);
});

test('GET /api/v1/projects/{id} retourne 404 pour un id inexistant', function () {
    $user = createOrgAdmin();
    loginAs($user);

    $this->getJson('/api/v1/projects/00000000-0000-0000-0000-000000000000', apiHeaders($user))
        ->assertStatus(404);
});

test('GET /api/v1/projects/{id} retourne 404 pour un projet d une autre org', function () {
    $org1 = createOrg();
    $org2 = createOrg();
    $user = createOrgAdmin($org1);

    $project = Project::factory()->create(['organization_id' => $org2->id]);

    loginAs($user);

    $this->getJson("/api/v1/projects/{$project->id}", apiHeaders($user))
        ->assertStatus(404);
});

/*
|--------------------------------------------------------------------------
| PUT /api/v1/projects/{id}
|--------------------------------------------------------------------------
*/

test('PUT /api/v1/projects/{id} met a jour un projet', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
        'title' => 'Ancien Titre',
    ]);

    loginAs($user);

    $this->putJson("/api/v1/projects/{$project->id}", [
        'title' => 'Nouveau Titre',
    ], apiHeaders($user))
        ->assertOk()
        ->assertJsonFragment(['title' => 'Nouveau Titre']);

    $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'Nouveau Titre']);
});

test('PUT /api/v1/projects/{id} retourne 404 pour un projet d une autre org', function () {
    $org1 = createOrg();
    $org2 = createOrg();
    $user = createOrgAdmin($org1);

    $project = Project::factory()->create(['organization_id' => $org2->id]);

    loginAs($user);

    $this->putJson("/api/v1/projects/{$project->id}", [
        'title' => 'Hacked',
    ], apiHeaders($user))
        ->assertStatus(404);
});

/*
|--------------------------------------------------------------------------
| DELETE /api/v1/projects/{id}
|--------------------------------------------------------------------------
*/

test('DELETE /api/v1/projects/{id} soft delete un projet', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    loginAs($user);

    $this->deleteJson("/api/v1/projects/{$project->id}", [], apiHeaders($user))
        ->assertOk()
        ->assertJsonFragment(['message' => 'Project deleted.']);

    $this->assertSoftDeleted('projects', ['id' => $project->id]);
});

test('DELETE /api/v1/projects/{id} retourne 404 pour un projet d une autre org', function () {
    $org1 = createOrg();
    $org2 = createOrg();
    $user = createOrgAdmin($org1);

    $project = Project::factory()->create(['organization_id' => $org2->id]);

    loginAs($user);

    $this->deleteJson("/api/v1/projects/{$project->id}", [], apiHeaders($user))
        ->assertStatus(404);
});

/*
|--------------------------------------------------------------------------
| GET /api/v1/activities
|--------------------------------------------------------------------------
*/

test('GET /api/v1/activities retourne les activites de l org', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    Activity::factory()->count(2)->create(['organization_id' => $org->id]);

    loginAs($user);

    $response = $this->getJson('/api/v1/activities', apiHeaders($user))
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta']);

    expect($response->json('data'))->toHaveCount(2);
});

test('GET /api/v1/activities ne retourne pas les activites d une autre org', function () {
    $org1 = createOrg();
    $org2 = createOrg();
    $user = createOrgAdmin($org1);

    Activity::factory()->create(['organization_id' => $org1->id]);
    Activity::factory()->create(['organization_id' => $org2->id]);

    loginAs($user);

    $response = $this->getJson('/api/v1/activities', apiHeaders($user))
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

/*
|--------------------------------------------------------------------------
| PUT /api/v1/activities/{id}
|--------------------------------------------------------------------------
*/

test('PUT /api/v1/activities/{id} met a jour une activite', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    $activity = Activity::factory()->create([
        'organization_id' => $org->id,
        'description' => 'Ancienne description',
    ]);

    loginAs($user);

    $this->putJson("/api/v1/activities/{$activity->id}", [
        'description' => 'Nouvelle description',
        'progress_percentage' => 50,
    ], apiHeaders($user))
        ->assertOk();

    expect($activity->fresh()->description)->toBe('Nouvelle description');
    expect($activity->fresh()->progress_percentage)->toBe(50);
});

test('PUT /api/v1/activities/{id} retourne 404 pour une activite d une autre org', function () {
    $org1 = createOrg();
    $org2 = createOrg();
    $user = createOrgAdmin($org1);

    $activity = Activity::factory()->create(['organization_id' => $org2->id]);

    loginAs($user);

    $this->putJson("/api/v1/activities/{$activity->id}", [
        'description' => 'Hacked',
    ], apiHeaders($user))
        ->assertStatus(404);
});

/*
|--------------------------------------------------------------------------
| GET /api/v1/stats
|--------------------------------------------------------------------------
*/

test('GET /api/v1/stats retourne les statistiques', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
        'status' => ProjectStatus::DRAFT,
    ]);
    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
        'status' => ProjectStatus::ACTIVE,
    ]);

    loginAs($user);

    $this->getJson('/api/v1/stats', apiHeaders($user))
        ->assertOk()
        ->assertJsonStructure([
            'projects' => ['total', 'active', 'completed', 'draft'],
            'activities' => ['total', 'completed', 'ongoing', 'overdue'],
            'budget' => ['planned', 'spent'],
            'members',
        ])
        ->assertJsonFragment([
            'projects' => [
                'total' => 2,
                'active' => 1,
                'completed' => 0,
                'draft' => 1,
            ],
        ]);
});

/*
|--------------------------------------------------------------------------
| GET /api/v1/notifications
|--------------------------------------------------------------------------
*/

test('GET /api/v1/notifications retourne la liste des notifications', function () {
    $user = createOrgAdmin();
    loginAs($user);

    $this->getJson('/api/v1/notifications', apiHeaders($user))
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta']);
});

/*
|--------------------------------------------------------------------------
| GET /api/v1/members
|--------------------------------------------------------------------------
*/

test('GET /api/v1/members retourne les membres de l org', function () {
    $org = createOrg();
    $admin = createOrgAdmin($org);
    createOrgUser($org);
    createOrgUser($org);

    loginAs($admin);

    $response = $this->getJson('/api/v1/members', apiHeaders($admin))
        ->assertOk();

    expect($response->json('data'))->toHaveCount(3);
});

test('GET /api/v1/members pour un independant retourne uniquement lui-meme', function () {
    $user = createIndependent();
    loginAs($user);

    $response = $this->getJson('/api/v1/members', apiHeaders($user))
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.id'))->toBe($user->id);
});
