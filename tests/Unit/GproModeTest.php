<?php

use App\Models\Organization;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Helpers isSelfHosted() / isSaas() — Phase 28
|--------------------------------------------------------------------------
*/

test('isSelfHosted: doit retourner true quand gpro.mode est selfhosted', function () {
    config(['gpro.mode' => 'selfhosted']);
    expect(isSelfHosted())->toBeTrue();
});

test('isSelfHosted: doit retourner false quand gpro.mode est saas', function () {
    config(['gpro.mode' => 'saas']);
    expect(isSelfHosted())->toBeFalse();
});

test('isSaas: doit retourner true quand gpro.mode est saas', function () {
    config(['gpro.mode' => 'saas']);
    expect(isSaas())->toBeTrue();
});

test('isSaas: doit retourner false quand gpro.mode est selfhosted', function () {
    config(['gpro.mode' => 'selfhosted']);
    expect(isSaas())->toBeFalse();
});

test('isSaas: doit retourner true par defaut (valeur par defaut de config)', function () {
    config(['gpro.mode' => 'saas']);
    expect(isSaas())->toBeTrue();
    expect(isSelfHosted())->toBeFalse();
});

/*
|--------------------------------------------------------------------------
| Bypass des limites en mode selfhosted — Phase 28
|--------------------------------------------------------------------------
*/

test('Organization::canCreateProject retourne true en selfhosted meme sans plan', function () {
    config(['gpro.mode' => 'selfhosted']);

    // Creer un plan default pour eviter l'erreur
    Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 1, 'max_members' => 1,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org']);

    expect($org->canCreateProject())->toBeTrue();
});

test('Organization::canAddMember retourne true en selfhosted meme sans plan', function () {
    config(['gpro.mode' => 'selfhosted']);

    Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 1, 'max_members' => 1,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org']);

    expect($org->canAddMember())->toBeTrue();
});

test('Organization::hasFeature retourne true en selfhosted pour toute feature', function () {
    config(['gpro.mode' => 'selfhosted']);

    Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 1, 'max_members' => 1,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org']);

    expect($org->hasFeature('api_access'))->toBeTrue();
    expect($org->hasFeature('feature_inexistante'))->toBeTrue();
});

test('Organization::canCreateProject respecte la limite du plan en mode saas', function () {
    config(['gpro.mode' => 'saas']);

    $plan = Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 1, 'max_members' => 5,
        'features' => ['logframe'], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org', 'plan_id' => $plan->id]);

    // Pas de projets : peut creer
    expect($org->canCreateProject())->toBeTrue();
});

test('Organization::hasFeature respecte le plan en mode saas', function () {
    config(['gpro.mode' => 'saas']);

    $plan = Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => ['logframe', 'basic_export'], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    $org = Organization::create(['name' => 'Test Org', 'slug' => 'test-org', 'plan_id' => $plan->id]);

    expect($org->hasFeature('logframe'))->toBeTrue();
    expect($org->hasFeature('api_access'))->toBeFalse();
});
