<?php

use App\Actions\Activities\UpdateActivityProgressAction;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Result;
use App\Models\SpecificObjective;
use App\Models\LogicalFramework;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ActivityProgressUpdatedNotification;

beforeEach(function () {
    $this->org = createOrg();
    $this->user = createOrgUser($this->org);
    loginAs($this->user);
});

it('updates activity progress and notifies stakeholders', function () {
    Notification::fake();

    // Setup hierarchy
    $project = Project::factory()->create(['organization_id' => $this->org->id, 'creator_user_id' => $this->user->id, 'status' => \App\Enums\ProjectStatus::ACTIVE]);
    $logFrame = LogicalFramework::factory()->create(['project_id' => $project->id]);
    $objective = SpecificObjective::factory()->create(['logical_framework_id' => $logFrame->id]);
    $result = Result::factory()->create(['specific_objective_id' => $objective->id]);
    
    $responsible = User::factory()->create(['organization_id' => $this->org->id]);
    
    $activity = Activity::factory()->create([
        'organization_id' => $this->org->id,
        'result_id' => $result->id,
        'responsible_user_id' => $responsible->id,
        'status' => \App\Enums\ActivityStatus::PENDING,
        'progress_percentage' => 0
    ]);

    $action = new UpdateActivityProgressAction();
    $action->execute($activity, [
        'status' => \App\Enums\ActivityStatus::ONGOING,
        'progress_percentage' => 50,
        'justification' => 'Travail bien avancé'
    ]);

    // Assertions base de données
    expect($activity->fresh()->status)->toBe(\App\Enums\ActivityStatus::ONGOING);
    expect($activity->fresh()->progress_percentage)->toEqual(50);
    expect($activity->fresh()->justification)->toBe('Travail bien avancé');

    // Assertions notifications
    Notification::assertSentTo(
        $responsible,
        ActivityProgressUpdatedNotification::class
    );
});

it('does not notify the user who performed the update', function () {
    Notification::fake();

    $project = Project::factory()->create(['organization_id' => $this->org->id, 'creator_user_id' => $this->user->id, 'status' => \App\Enums\ProjectStatus::ACTIVE]);
    $logFrame = LogicalFramework::factory()->create(['project_id' => $project->id]);
    $objective = SpecificObjective::factory()->create(['logical_framework_id' => $logFrame->id]);
    $result = Result::factory()->create(['specific_objective_id' => $objective->id]);
    
    // L'utilisateur connecté est le responsable
    $activity = Activity::factory()->create([
        'organization_id' => $this->org->id,
        'result_id' => $result->id,
        'responsible_user_id' => $this->user->id,
    ]);

    $action = new UpdateActivityProgressAction();
    $action->execute($activity, ['progress_percentage' => 30]);

    // L'utilisateur ne doit pas recevoir de notification s'il a fait l'update lui-même
    Notification::assertNotSentTo(
        $this->user,
        ActivityProgressUpdatedNotification::class
    );
});
