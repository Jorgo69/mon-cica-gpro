<?php

use App\Models\Organization;
use App\Models\Project;
use App\Models\ShareToken;

test('doit afficher le projet partage avec un token valide', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    $shareToken = ShareToken::create([
        'project_id' => $project->id,
        'created_by_user_id' => $user->id,
        'label' => 'Test link',
        'is_active' => true,
        'expires_at' => now()->addDays(7),
    ]);

    $this->get("/shared/project/{$shareToken->token}")
        ->assertStatus(200);
});

test('doit retourner 403 quand le token est expire', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    $shareToken = ShareToken::create([
        'project_id' => $project->id,
        'created_by_user_id' => $user->id,
        'label' => 'Expired link',
        'is_active' => true,
        'expires_at' => now()->subDay(),
    ]);

    $this->get("/shared/project/{$shareToken->token}")
        ->assertStatus(403);
});

test('doit retourner 403 quand le token est desactive', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    $shareToken = ShareToken::create([
        'project_id' => $project->id,
        'created_by_user_id' => $user->id,
        'label' => 'Inactive link',
        'is_active' => false,
        'expires_at' => now()->addDays(7),
    ]);

    $this->get("/shared/project/{$shareToken->token}")
        ->assertStatus(403);
});

test('doit retourner 404 quand le token n\'existe pas', function () {
    $this->get('/shared/project/token-inexistant-xyz123')
        ->assertStatus(404);
});

test('doit incrementer le compteur de vues a chaque visite', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    $shareToken = ShareToken::create([
        'project_id' => $project->id,
        'created_by_user_id' => $user->id,
        'label' => 'Counter link',
        'is_active' => true,
        'expires_at' => now()->addDays(7),
    ]);

    expect((int) $shareToken->view_count)->toBe(0);

    $this->get("/shared/project/{$shareToken->token}");

    $shareToken->refresh();
    expect((int) $shareToken->view_count)->toBe(1);
});
