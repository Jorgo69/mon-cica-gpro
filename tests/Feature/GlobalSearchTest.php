<?php

use App\Services\Search\GlobalSearchService;
use App\Models\Project;
use App\Models\User;
use App\Models\Activity;
use App\Models\LogicalFramework;
use App\Models\SpecificObjective;
use App\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    [$this->org, $this->user] = loginAsUser();
});

it('can search for projects within the organization', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'title' => 'Mon Super Projet',
        'project_code' => 'PRJ-001'
    ]);

    // Projet d'une autre organisation (ne doit pas être trouvé)
    $otherOrg = \App\Models\Organization::factory()->create();
    Project::factory()->create([
        'organization_id' => $otherOrg->id,
        'title' => 'Autre Projet'
    ]);

    $service = new GlobalSearchService();
    $results = $service->search('Super');

    expect($results)->toHaveCount(1);
    expect($results[0]['title'])->toBe('Mon Super Projet');
    expect($results[0]['type'])->toBe('Projet');
});

it('can search for users within the organization', function () {
    $otherUser = User::factory()->create([
        'organization_id' => $this->org->id,
        'name' => 'Alice Member',
        'email' => 'alice@example.com'
    ]);

    $service = new GlobalSearchService();
    $results = $service->search('Alice');

    expect($results)->toHaveCount(1);
    expect($results[0]['title'])->toBe('Alice Member');
    expect($results[0]['type'])->toBe('Membre');
});

it('can search for activities within the organization', function () {
    $project = Project::factory()->create(['organization_id' => $this->org->id]);
    $logFrame = LogicalFramework::factory()->create(['project_id' => $project->id]);
    $objective = SpecificObjective::factory()->create(['logical_framework_id' => $logFrame->id]);
    $result = Result::factory()->create(['specific_objective_id' => $objective->id]);
    
    $activity = Activity::factory()->create([
        'organization_id' => $this->org->id,
        'result_id' => $result->id,
        'description' => 'Tâche de développement'
    ]);

    $service = new GlobalSearchService();
    $results = $service->search('développement');

    expect($results)->toHaveCount(1);
    expect($results[0]['title'])->toBe('Tâche de développement');
    expect($results[0]['type'])->toBe('Activité');
});
