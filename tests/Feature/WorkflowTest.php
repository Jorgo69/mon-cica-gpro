<?php

use App\Enums\AccountType;
use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\ProjectApproval;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    seedPermissions();
    Notification::fake();

    $this->org = createOrg();
    $this->admin = createOrgAdmin($this->org);
    $this->creator = createOrgUser($this->org);

    // Assign Spatie roles (global, team_id=null)
    setPermissionsTeamId(null);
    $this->admin->assignRole('ORG_ADMIN');
    $this->creator->assignRole('MEMBER');
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    $this->service = new WorkflowService();
});

// --- Transitions autorisees ---

test('le createur peut soumettre un projet DRAFT', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->creator->id,
        'status' => ProjectStatus::DRAFT,
    ]);

    $approval = $this->service->submit($project, $this->creator, 'Pret pour review');

    expect($project->fresh()->status)->toBe(ProjectStatus::SUBMITTED);
    expect($approval)->toBeInstanceOf(ProjectApproval::class);
    expect($approval->action)->toBe('submit');
    expect($approval->from_status)->toBe(ProjectStatus::DRAFT->value);
    expect($approval->to_status)->toBe(ProjectStatus::SUBMITTED->value);
    expect($approval->comment)->toBe('Pret pour review');
});

test('un admin peut demarrer la review d un projet SUBMITTED', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->creator->id,
        'status' => ProjectStatus::SUBMITTED,
    ]);

    $approval = $this->service->startReview($project, $this->admin);

    expect($project->fresh()->status)->toBe(ProjectStatus::UNDER_REVIEW);
    expect($approval->action)->toBe('review');
});

test('un admin peut approuver un projet UNDER_REVIEW', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->creator->id,
        'status' => ProjectStatus::UNDER_REVIEW,
    ]);

    $approval = $this->service->approve($project, $this->admin, 'Approuve');

    expect($project->fresh()->status)->toBe(ProjectStatus::APPROVED);
    expect($approval->action)->toBe('approve');
});

test('un admin peut rejeter un projet UNDER_REVIEW', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->creator->id,
        'status' => ProjectStatus::UNDER_REVIEW,
    ]);

    $approval = $this->service->reject($project, $this->admin, 'Pas assez detaille');

    expect($project->fresh()->status)->toBe(ProjectStatus::REJECTED);
    expect($approval->action)->toBe('reject');
});

test('un admin peut activer un projet APPROVED', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->creator->id,
        'status' => ProjectStatus::APPROVED,
    ]);

    $approval = $this->service->activate($project, $this->admin);

    expect($project->fresh()->status)->toBe(ProjectStatus::ACTIVE);
    expect($approval->action)->toBe('activate');
});

test('un projet rejete peut revenir en brouillon par le createur', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->creator->id,
        'status' => ProjectStatus::REJECTED,
    ]);

    $approval = $this->service->revertToDraft($project, $this->creator);

    expect($project->fresh()->status)->toBe(ProjectStatus::DRAFT);
    expect($approval->action)->toBe('revert_to_draft');
});

// --- Transitions interdites ---

test('un projet DRAFT ne peut pas passer directement a ACTIVE', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->admin->id,
        'status' => ProjectStatus::DRAFT,
    ]);

    expect(fn () => $this->service->activate($project, $this->admin))
        ->toThrow(\InvalidArgumentException::class);
});

test('un projet COMPLETED ne peut pas transitionner', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->admin->id,
        'status' => ProjectStatus::COMPLETED,
    ]);

    expect(fn () => $this->service->submit($project, $this->admin))
        ->toThrow(\InvalidArgumentException::class);
});

test('un projet APPROVED ne peut pas etre re-approuve', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->admin->id,
        'status' => ProjectStatus::APPROVED,
    ]);

    expect(fn () => $this->service->approve($project, $this->admin))
        ->toThrow(\InvalidArgumentException::class);
});

// --- Permissions ---

test('un membre sans permission edit-projects ne peut pas approuver', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->admin->id,
        'status' => ProjectStatus::UNDER_REVIEW,
    ]);

    // $this->creator has MEMBER role = no edit-projects permission
    expect(fn () => $this->service->approve($project, $this->creator))
        ->toThrow(\InvalidArgumentException::class);
});

test('un non-createur non-admin ne peut pas soumettre', function () {
    $otherUser = createOrgUser($this->org);
    setPermissionsTeamId(null);
    $otherUser->assignRole('MEMBER');
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->creator->id,
        'status' => ProjectStatus::DRAFT,
    ]);

    expect(fn () => $this->service->submit($project, $otherUser))
        ->toThrow(\InvalidArgumentException::class);
});

test('seul un admin peut activer un projet approuve', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->creator->id,
        'status' => ProjectStatus::APPROVED,
    ]);

    // MEMBER role cannot activate
    expect(fn () => $this->service->activate($project, $this->creator))
        ->toThrow(\InvalidArgumentException::class);
});

// --- ProjectApproval record ---

test('la transition cree un enregistrement ProjectApproval complet', function () {
    $project = Project::factory()->create([
        'organization_id' => $this->org->id,
        'creator_user_id' => $this->creator->id,
        'status' => ProjectStatus::DRAFT,
    ]);

    $this->service->submit($project, $this->creator, 'Mon commentaire');

    $this->assertDatabaseHas('project_approvals', [
        'project_id' => $project->id,
        'user_id' => $this->creator->id,
        'action' => 'submit',
        'from_status' => ProjectStatus::DRAFT->value,
        'to_status' => ProjectStatus::SUBMITTED->value,
        'comment' => 'Mon commentaire',
    ]);
});
