<?php

use App\Models\Plan;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Plan model — Phase 29
|--------------------------------------------------------------------------
*/

// ─── Creation et UUID ───────────────────────────────────

test('Plan: cree un plan avec un UUID auto-genere', function () {
    $plan = Plan::create([
        'name' => 'Test', 'slug' => 'test', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 5, 'max_members' => 10,
        'features' => ['logframe'], 'is_default' => false, 'is_active' => true, 'sort_order' => 1,
    ]);

    expect($plan->id)->toBeUuid();
});

// ─── Helpers ────────────────────────────────────────────

test('Plan::maxProjects retourne la valeur max_projects', function () {
    $plan = Plan::create([
        'name' => 'Pro', 'slug' => 'pro', 'price' => 15000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 20, 'max_members' => 50,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 2,
    ]);

    expect($plan->maxProjects())->toBe(20);
});

test('Plan::maxMembers retourne la valeur max_members', function () {
    $plan = Plan::create([
        'name' => 'Pro', 'slug' => 'pro', 'price' => 15000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 20, 'max_members' => 50,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 2,
    ]);

    expect($plan->maxMembers())->toBe(50);
});

test('Plan::hasFeature retourne true si la feature est presente', function () {
    $plan = Plan::create([
        'name' => 'Pro', 'slug' => 'pro', 'price' => 15000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 20, 'max_members' => 50,
        'features' => ['logframe', 'pdf_export', 'excel_export'], 'is_default' => false, 'is_active' => true, 'sort_order' => 2,
    ]);

    expect($plan->hasFeature('logframe'))->toBeTrue();
    expect($plan->hasFeature('pdf_export'))->toBeTrue();
});

test('Plan::hasFeature retourne false si la feature est absente', function () {
    $plan = Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => ['logframe'], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    expect($plan->hasFeature('api_access'))->toBeFalse();
    expect($plan->hasFeature('priority_support'))->toBeFalse();
});

test('Plan::isUnlimited retourne true quand projets et membres sont illimites', function () {
    $plan = Plan::create([
        'name' => 'Enterprise', 'slug' => 'enterprise', 'price' => 45000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => -1, 'max_members' => -1,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 3,
    ]);

    expect($plan->isUnlimited())->toBeTrue();
});

test('Plan::isUnlimited retourne false quand un seul est limite', function () {
    $plan = Plan::create([
        'name' => 'Pro', 'slug' => 'pro', 'price' => 15000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 20, 'max_members' => -1,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 2,
    ]);

    expect($plan->isUnlimited())->toBeFalse();
});

test('Plan::isUnlimited retourne false quand les deux sont limites', function () {
    $plan = Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    expect($plan->isUnlimited())->toBeFalse();
});

test('Plan::formattedPrice retourne le label gratuit quand price est 0', function () {
    $plan = Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    // Utilise la traduction plans.free_price
    expect($plan->formattedPrice())->toBeString()->not->toBeEmpty();
});

test('Plan::formattedPrice retourne un prix formate avec devise quand price > 0', function () {
    $plan = Plan::create([
        'name' => 'Pro', 'slug' => 'pro', 'price' => 15000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 20, 'max_members' => 50,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 2,
    ]);

    $formatted = $plan->formattedPrice();
    expect($formatted)->toContain('FCFA');
    expect($formatted)->toContain('15');
});

// ─── Static helpers ─────────────────────────────────────

test('Plan::defaultPlan retourne le plan marque is_default', function () {
    Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);
    Plan::create([
        'name' => 'Pro', 'slug' => 'pro', 'price' => 15000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 20, 'max_members' => 50,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 2,
    ]);

    $default = Plan::defaultPlan();
    expect($default->slug)->toBe('free');
    expect($default->is_default)->toBeTrue();
});

test('Plan::defaultPlan fallback sur le plan free si aucun is_default', function () {
    Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 1,
    ]);

    $default = Plan::defaultPlan();
    expect($default->slug)->toBe('free');
});

test('Plan::findBySlug retourne le plan correspondant au slug', function () {
    Plan::create([
        'name' => 'Pro', 'slug' => 'pro', 'price' => 15000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 20, 'max_members' => 50,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 2,
    ]);

    $plan = Plan::findBySlug('pro');
    expect($plan)->not->toBeNull();
    expect($plan->name)->toBe('Pro');
});

test('Plan::findBySlug retourne null si le slug n existe pas', function () {
    expect(Plan::findBySlug('inexistant'))->toBeNull();
});

// ─── Scopes ─────────────────────────────────────────────

test('Plan scope active filtre les plans inactifs', function () {
    Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);
    Plan::create([
        'name' => 'Legacy', 'slug' => 'legacy', 'price' => 5000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 5, 'max_members' => 10,
        'features' => [], 'is_default' => false, 'is_active' => false, 'sort_order' => 99,
    ]);

    $active = Plan::active()->get();
    expect($active)->toHaveCount(1);
    expect($active->first()->slug)->toBe('free');
});

test('Plan scope ordered trie par sort_order croissant', function () {
    Plan::create([
        'name' => 'Enterprise', 'slug' => 'enterprise', 'price' => 45000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => -1, 'max_members' => -1,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 3,
    ]);
    Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);
    Plan::create([
        'name' => 'Pro', 'slug' => 'pro', 'price' => 15000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 20, 'max_members' => 50,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 2,
    ]);

    $ordered = Plan::ordered()->get();
    expect($ordered->pluck('slug')->toArray())->toBe(['free', 'pro', 'enterprise']);
});

// ─── Organization::currentPlan ──────────────────────────

test('Organization::currentPlan retourne le plan associe via plan_id', function () {
    config(['gpro.mode' => 'saas']);

    $pro = Plan::create([
        'name' => 'Pro', 'slug' => 'pro', 'price' => 15000, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 20, 'max_members' => 50,
        'features' => [], 'is_default' => false, 'is_active' => true, 'sort_order' => 2,
    ]);
    Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    $org = Organization::create(['name' => 'Test', 'slug' => 'test', 'plan_id' => $pro->id]);

    expect($org->currentPlan()->slug)->toBe('pro');
});

test('Organization::currentPlan retourne le plan default si pas de plan_id', function () {
    config(['gpro.mode' => 'saas']);

    Plan::create([
        'name' => 'Free', 'slug' => 'free', 'price' => 0, 'currency' => 'FCFA',
        'billing_period' => 'month', 'max_projects' => 2, 'max_members' => 5,
        'features' => [], 'is_default' => true, 'is_active' => true, 'sort_order' => 1,
    ]);

    $org = Organization::create(['name' => 'Test', 'slug' => 'test']);

    expect($org->currentPlan()->slug)->toBe('free');
});
