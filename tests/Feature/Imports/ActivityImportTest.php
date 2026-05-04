<?php

use App\Enums\ActivityStatus;
use App\Imports\ActivityImport;
use App\Models\Organization;
use App\Models\Result;
use App\Models\User;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->org = createOrg();
    $this->user = createOrgAdmin($this->org);
    $this->result = Result::factory()->create();
    $this->import = new ActivityImport($this->result->id, $this->org->id, $this->user->id);
});

// --- Preview ---

test('preview retourne valid=true pour une ligne avec description', function () {
    $rows = collect([
        ['Formation equipe', 'user@test.com', 'En Cours', '01/01/2025', '30/06/2025', '50000', '25'],
    ]);

    $result = $this->import->preview($rows);

    expect($result)->toHaveCount(1);
    expect($result[0]['valid'])->toBeTrue();
    expect($result[0]['description'])->toBe('Formation equipe');
});

test('preview retourne valid=false quand la description est manquante', function () {
    $rows = collect([
        ['', 'user@test.com', '', '', '', '0', '0'],
    ]);

    $result = $this->import->preview($rows);

    expect($result[0]['valid'])->toBeFalse();
    expect($result[0]['errors'])->not->toBeEmpty();
});

test('preview signale un budget non numerique', function () {
    $rows = collect([
        ['Activite', '', '', '', '', 'pas-un-nombre', '0'],
    ]);

    $result = $this->import->preview($rows);

    expect($result[0]['valid'])->toBeFalse();
});

// --- Import ---

test('import cree les activites en base de donnees', function () {
    $rows = collect([
        ['Activite 1', '', '', '01/01/2025', '30/06/2025', '100000', '50'],
        ['Activite 2', '', '', '', '', '0', '0'],
    ]);

    $result = $this->import->import($rows);

    expect($result['imported'])->toBe(2);
    expect($result['errors'])->toBeEmpty();
    $this->assertDatabaseHas('activities', ['description' => 'Activite 1', 'budget' => 100000]);
    $this->assertDatabaseHas('activities', ['description' => 'Activite 2']);
});

test('import assigne le result_id et organization_id du constructeur', function () {
    $rows = collect([
        ['Activite rattachee', '', '', '', '', '0', '0'],
    ]);

    $this->import->import($rows);

    $this->assertDatabaseHas('activities', [
        'description' => 'Activite rattachee',
        'result_id' => $this->result->id,
        'organization_id' => $this->org->id,
    ]);
});

test('import ignore les lignes sans description et enregistre les erreurs', function () {
    $rows = collect([
        ['Activite OK', '', '', '', '', '0', '0'],
        ['', '', '', '', '', '0', '0'],   // description vide -> invalide
    ]);

    $result = $this->import->import($rows);

    expect($result['imported'])->toBe(1);
    expect($result['errors'])->not->toBeEmpty();
});

test('import plafonne le progres a 100', function () {
    $rows = collect([
        ['Activite', '', '', '', '', '0', '200'],
    ]);

    $this->import->import($rows);

    $activity = \App\Models\Activity::where('description', 'Activite')->first();
    expect($activity->progress_percentage)->toBe(100);
});

test('import avec progres negatif retourne 0', function () {
    $rows = collect([
        ['Activite', '', '', '', '', '0', '-50'],
    ]);

    $this->import->import($rows);

    $activity = \App\Models\Activity::where('description', 'Activite')->first();
    expect($activity->progress_percentage)->toBe(0);
});

// --- resolveUser ---

test('resolveUser trouve un utilisateur par email dans la meme organisation', function () {
    $member = createUser(['email' => 'membre@org.test'], $this->org);

    $rows = collect([
        ['Activite avec responsable', 'membre@org.test', '', '', '', '0', '0'],
    ]);

    $this->import->import($rows);

    $this->assertDatabaseHas('activities', [
        'description' => 'Activite avec responsable',
        'responsible_user_id' => $member->id,
    ]);
});

test('resolveUser retourne null pour un email inconnu', function () {
    $rows = collect([
        ['Activite email inconnu', 'inconnu@nowhere.test', '', '', '', '0', '0'],
    ]);

    $this->import->import($rows);

    $this->assertDatabaseHas('activities', [
        'description' => 'Activite email inconnu',
        'responsible_user_id' => null,
    ]);
});

// --- Status ---

test('resolveStatus mappe En Cours vers ONGOING', function () {
    $rows = collect([
        ['Activite', '', 'En Cours', '', '', '0', '0'],
    ]);

    $this->import->import($rows);

    $activity = \App\Models\Activity::where('description', 'Activite')->first();
    expect($activity->status)->toBe(ActivityStatus::ONGOING);
});

test('resolveStatus retourne PENDING pour un status inconnu', function () {
    $rows = collect([
        ['Activite', '', 'blabla', '', '', '0', '0'],
    ]);

    $this->import->import($rows);

    $activity = \App\Models\Activity::where('description', 'Activite')->first();
    expect($activity->status)->toBe(ActivityStatus::PENDING);
});
