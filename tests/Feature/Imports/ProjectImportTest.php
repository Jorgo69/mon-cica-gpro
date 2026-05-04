<?php

use App\Enums\ProjectStatus;
use App\Imports\ProjectImport;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->org = createOrg();
    $this->user = createOrgAdmin($this->org);
    $this->import = new ProjectImport($this->org->id, $this->user->id);
});

// --- Preview ---

test('preview retourne valid=true pour une ligne avec titre', function () {
    $rows = collect([
        ['Mon Projet', 'PRJ-001', 'Description', 'Brouillon', '01/01/2025', '31/12/2025', 'XOF'],
    ]);

    $result = $this->import->preview($rows);

    expect($result)->toHaveCount(1);
    expect($result[0]['valid'])->toBeTrue();
    expect($result[0]['title'])->toBe('Mon Projet');
    expect($result[0]['row'])->toBe(2);
});

test('preview retourne valid=false quand le titre est manquant', function () {
    $rows = collect([
        ['', 'PRJ-001', 'Description', '', '', '', 'XOF'],
    ]);

    $result = $this->import->preview($rows);

    expect($result)->toHaveCount(1);
    expect($result[0]['valid'])->toBeFalse();
    expect($result[0]['errors'])->not->toBeEmpty();
});

test('preview ignore les lignes completement vides', function () {
    $rows = collect([
        ['', '', '', '', '', '', ''],
        ['Mon Projet', '', '', '', '', '', 'XOF'],
    ]);

    $result = $this->import->preview($rows);

    expect($result)->toHaveCount(1);
    expect($result[0]['title'])->toBe('Mon Projet');
});

test('preview signale une date invalide', function () {
    $rows = collect([
        ['Projet', '', '', '', 'pas-une-date', '', 'XOF'],
    ]);

    $result = $this->import->preview($rows);

    expect($result[0]['valid'])->toBeFalse();
    expect($result[0]['errors'])->not->toBeEmpty();
});

// --- Import ---

test('import cree les projets en base de donnees', function () {
    $rows = collect([
        ['Projet Alpha', 'PRJ-A', 'Desc', 'Brouillon', '15/03/2025', '15/09/2025', 'EUR'],
        ['Projet Beta', 'PRJ-B', '', '', '2025-01-01', '2025-12-31', 'XOF'],
    ]);

    $result = $this->import->import($rows);

    expect($result['imported'])->toBe(2);
    expect($result['errors'])->toBeEmpty();
    $this->assertDatabaseHas('projects', ['title' => 'Projet Alpha', 'project_code' => 'PRJ-A']);
    $this->assertDatabaseHas('projects', ['title' => 'Projet Beta', 'project_code' => 'PRJ-B']);
});

test('import ignore les lignes invalides et compte les erreurs', function () {
    $rows = collect([
        ['Projet Valide', 'PRJ-V', '', '', '', '', 'XOF'],
        ['', '', '', '', '', '', ''],       // vide -> ignore
        ['', 'PRJ-X', '', '', '', '', 'XOF'], // pas de titre -> erreur
    ]);

    $result = $this->import->import($rows);

    expect($result['imported'])->toBe(1);
    expect($result['errors'])->not->toBeEmpty();
});

test('import assigne organization_id et creator_user_id du constructeur', function () {
    $rows = collect([
        ['Projet Org', '', '', '', '', '', 'XOF'],
    ]);

    $this->import->import($rows);

    $this->assertDatabaseHas('projects', [
        'title' => 'Projet Org',
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->user->id,
    ]);
});

test('import genere un code automatique si absent', function () {
    $rows = collect([
        ['Projet Sans Code', '', '', '', '', '', 'XOF'],
    ]);

    $this->import->import($rows);

    $project = \App\Models\Project::where('title', 'Projet Sans Code')->first();
    expect($project->project_code)->toStartWith('IMP-');
    expect(strlen($project->project_code))->toBe(10); // IMP- + 6 chars
});

// --- Dates ---

test('import parse correctement une date au format slash jour-mois-annee', function () {
    $rows = collect([
        ['Projet Date Slash', '', '', '', '25/12/2025', '', 'XOF'],
    ]);

    $this->import->import($rows);

    $project = \App\Models\Project::where('title', 'Projet Date Slash')->first();
    expect($project->start_date->format('Y-m-d'))->toBe('2025-12-25');
});

test('import parse correctement une date au format ISO annee-mois-jour', function () {
    $rows = collect([
        ['Projet Date ISO', '', '', '', '2025-06-15', '', 'XOF'],
    ]);

    $this->import->import($rows);

    $project = \App\Models\Project::where('title', 'Projet Date ISO')->first();
    expect($project->start_date->format('Y-m-d'))->toBe('2025-06-15');
});

test('import parse correctement une date au format tiret jour-mois-annee', function () {
    $rows = collect([
        ['Projet Date Tiret', '', '', '', '15-06-2025', '', 'XOF'],
    ]);

    $this->import->import($rows);

    $project = \App\Models\Project::where('title', 'Projet Date Tiret')->first();
    expect($project->start_date->format('Y-m-d'))->toBe('2025-06-15');
});

// --- Status mapping ---

test('resolveStatus mappe une valeur connue vers le bon enum', function () {
    $rows = collect([
        ['Projet Status', '', '', 'En cours', '', '', 'XOF'],
    ]);

    $this->import->import($rows);

    $project = \App\Models\Project::where('title', 'Projet Status')->first();
    expect($project->status)->toBe(ProjectStatus::ACTIVE);
});

test('resolveStatus retourne DRAFT pour un status inconnu', function () {
    $rows = collect([
        ['Projet Inconnu', '', '', 'quelque-chose-inconnu', '', '', 'XOF'],
    ]);

    $this->import->import($rows);

    $project = \App\Models\Project::where('title', 'Projet Inconnu')->first();
    expect($project->status)->toBe(ProjectStatus::DRAFT);
});

test('resolveStatus est insensible a la casse', function () {
    $rows = collect([
        ['Projet Casse', '', '', 'BROUILLON', '', '', 'XOF'],
    ]);

    $this->import->import($rows);

    $project = \App\Models\Project::where('title', 'Projet Casse')->first();
    expect($project->status)->toBe(ProjectStatus::DRAFT);
});
