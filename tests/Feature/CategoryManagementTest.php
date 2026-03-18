<?php

use App\Actions\Admin\Category\SaveCategoryAction;
use App\Services\Admin\CategoryQueryService;
use App\Models\GeneralAdministration;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    [$this->org, $this->user] = loginAsUser();
});

// ────────────────────────────────
// SaveCategoryAction
// ────────────────────────────────

it('can create a new category', function () {
    $action = new SaveCategoryAction();

    $category = $action->execute([
        'name' => 'Infrastructure',
        'description' => 'Projets liés aux infrastructures',
    ]);

    expect($category)->toBeInstanceOf(GeneralAdministration::class);
    expect($category->name)->toBe('Infrastructure');
    expect($category->type)->toBe('project_type_category');

    $this->assertDatabaseHas('general_administrations', [
        'name' => 'Infrastructure',
        'type' => 'project_type_category',
    ]);
});

it('can update an existing category', function () {
    $existing = GeneralAdministration::create([
        'name' => 'Ancien Nom',
        'description' => 'Ancienne description',
        'type' => 'project_type_category',
    ]);

    $action = new SaveCategoryAction();
    $updated = $action->execute([
        'name' => 'Nouveau Nom',
        'description' => 'Nouvelle description',
    ], $existing->id);

    expect($updated->name)->toBe('Nouveau Nom');
    expect($updated->description)->toBe('Nouvelle description');
});

// ────────────────────────────────
// CategoryQueryService
// ────────────────────────────────

it('lists only project_type_category entries', function () {
    GeneralAdministration::create(['name' => 'Cat A', 'type' => 'project_type_category']);
    GeneralAdministration::create(['name' => 'Other', 'type' => 'some_other_type']);

    $service = new CategoryQueryService();
    $results = $service->list();

    expect($results->total())->toBe(1);
    expect($results->first()->name)->toBe('Cat A');
});

it('filters categories by search term', function () {
    GeneralAdministration::create(['name' => 'Education', 'type' => 'project_type_category']);
    GeneralAdministration::create(['name' => 'Santé', 'type' => 'project_type_category']);

    $service = new CategoryQueryService();
    $results = $service->list(search: 'Educ');

    expect($results->total())->toBe(1);
    expect($results->first()->name)->toBe('Education');
});

it('sorts categories by name', function () {
    GeneralAdministration::create(['name' => 'Zebra', 'type' => 'project_type_category']);
    GeneralAdministration::create(['name' => 'Alpha', 'type' => 'project_type_category']);

    $service = new CategoryQueryService();
    $results = $service->list(sortField: 'name', sortDirection: 'asc');

    expect($results->first()->name)->toBe('Alpha');
});
