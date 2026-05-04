<?php

use App\Models\Budget;
use App\Models\Expense;
use App\Models\Project;
use App\Services\BudgetTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('projectSummary retourne planned, spent, remaining et used_percent', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    loginAs($user);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    Budget::create([
        'organization_id' => $org->id,
        'project_id' => $project->id,
        'creator_user_id' => $user->id,
        'description' => 'Budget A',
        'total_cost' => 10000,
    ]);

    Expense::create([
        'organization_id' => $org->id,
        'project_id' => $project->id,
        'creator_user_id' => $user->id,
        'description' => 'Depense 1',
        'amount' => 3000,
        'expense_date' => now(),
    ]);

    $summary = BudgetTrackingService::projectSummary($project);

    expect($summary['planned'])->toBe(10000.0)
        ->and($summary['spent'])->toBe(3000.0)
        ->and($summary['remaining'])->toBe(7000.0)
        ->and($summary['used_percent'])->toBe(30.0)
        ->and($summary['is_over_budget'])->toBeFalse();
});

test('projet sans budget retourne des zeros dans projectSummary', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    loginAs($user);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    $summary = BudgetTrackingService::projectSummary($project);

    expect($summary['planned'])->toBe(0.0)
        ->and($summary['spent'])->toBe(0.0)
        ->and($summary['remaining'])->toBe(0.0)
        ->and($summary['used_percent'])->toBe(0);
});

test('projet sans expenses retourne burnRate a zero', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    loginAs($user);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    $rate = BudgetTrackingService::burnRate($project);

    expect($rate['daily'])->toBe(0)
        ->and($rate['monthly'])->toBe(0)
        ->and($rate['projected_total'])->toBe(0)
        ->and($rate['days_remaining'])->toBeNull();
});

test('budget avec expenses partielles donne le bon pourcentage', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);
    loginAs($user);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    Budget::create([
        'organization_id' => $org->id,
        'project_id' => $project->id,
        'creator_user_id' => $user->id,
        'description' => 'Budget total',
        'total_cost' => 50000,
    ]);

    Budget::create([
        'organization_id' => $org->id,
        'project_id' => $project->id,
        'creator_user_id' => $user->id,
        'description' => 'Budget complementaire',
        'total_cost' => 50000,
    ]);

    Expense::create([
        'organization_id' => $org->id,
        'project_id' => $project->id,
        'creator_user_id' => $user->id,
        'description' => 'Achat materiel',
        'amount' => 15000,
        'expense_date' => now()->subDays(5),
    ]);

    Expense::create([
        'organization_id' => $org->id,
        'project_id' => $project->id,
        'creator_user_id' => $user->id,
        'description' => 'Honoraires',
        'amount' => 10000,
        'expense_date' => now(),
    ]);

    $summary = BudgetTrackingService::projectSummary($project);

    expect($summary['planned'])->toBe(100000.0)
        ->and($summary['spent'])->toBe(25000.0)
        ->and($summary['remaining'])->toBe(75000.0)
        ->and($summary['used_percent'])->toBe(25.0)
        ->and($summary['is_over_budget'])->toBeFalse();
});
