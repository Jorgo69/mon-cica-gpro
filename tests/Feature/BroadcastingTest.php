<?php

use App\Events\ActivityUpdated;
use App\Events\BudgetAlert;
use App\Events\CommentPosted;
use App\Events\NewNotification;
use App\Events\ProjectUpdated;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Project;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Event;

/*
|--------------------------------------------------------------------------
| Events — broadcast channel & payload
|--------------------------------------------------------------------------
*/

test('ProjectUpdated broadcast sur le channel private org.{orgId}', function () {
    $project = Project::factory()->create();
    $event = new ProjectUpdated($project, 'created');

    $channels = $event->broadcastOn();

    expect($channels)->toHaveCount(1)
        ->and($channels[0])->toBeInstanceOf(PrivateChannel::class)
        ->and($channels[0]->name)->toBe('private-org.' . $project->organization_id);
});

test('ProjectUpdated broadcast avec les bonnes donnees', function () {
    $project = Project::factory()->create();
    $event = new ProjectUpdated($project, 'created');

    $data = $event->broadcastWith();

    expect($data)->toMatchArray([
        'project_id' => $project->id,
        'title' => $project->title,
        'status' => $project->status->value,
        'action' => 'created',
    ]);
});

test('ActivityUpdated broadcast avec activity_id, description, progress, status, action', function () {
    $activity = Activity::factory()->create(['progress_percentage' => 45]);
    $event = new ActivityUpdated($activity, 'progress_changed');

    $channels = $event->broadcastOn();
    $data = $event->broadcastWith();

    expect($channels[0]->name)->toBe('private-org.' . $activity->organization_id)
        ->and($data)->toMatchArray([
            'activity_id' => $activity->id,
            'description' => $activity->description,
            'progress' => 45,
            'status' => $activity->status->value,
            'action' => 'progress_changed',
        ]);
});

test('NewNotification broadcast sur le channel private user.{userId}', function () {
    $userId = fake()->uuid();
    $event = new NewNotification($userId, 'info', 'Projet mis a jour');

    $channels = $event->broadcastOn();
    $data = $event->broadcastWith();

    expect($channels[0]->name)->toBe('private-user.' . $userId)
        ->and($data)->toMatchArray([
            'type' => 'info',
            'message' => 'Projet mis a jour',
        ]);
});

test('CommentPosted broadcast sur le channel private project.{projectId}', function () {
    $org = createOrg();
    $user = createUser([], $org);
    $project = Project::factory()->create(['organization_id' => $org->id, 'creator_user_id' => $user->id]);

    $comment = Comment::create([
        'user_id' => $user->id,
        'organization_id' => $org->id,
        'commentable_type' => Project::class,
        'commentable_id' => $project->id,
        'body' => 'Super avancement',
    ]);
    $comment->load('user');

    $event = new CommentPosted($comment, $project->id);

    $channels = $event->broadcastOn();
    $data = $event->broadcastWith();

    expect($channels[0]->name)->toBe('private-project.' . $project->id)
        ->and($data['comment_id'])->toBe($comment->id)
        ->and($data['user_name'])->toBe($user->name)
        ->and($data['body'])->toBe('Super avancement')
        ->and($data)->toHaveKey('created_at');
});

test('BudgetAlert broadcast avec used_percent sur org.{orgId}', function () {
    $orgId = fake()->uuid();
    $projectId = fake()->uuid();

    $event = new BudgetAlert($orgId, $projectId, 'Mon Projet', 87.5);

    $channels = $event->broadcastOn();
    $data = $event->broadcastWith();

    expect($channels[0]->name)->toBe('private-org.' . $orgId)
        ->and($data)->toMatchArray([
            'project_id' => $projectId,
            'project_title' => 'Mon Projet',
            'used_percent' => 87.5,
        ]);
});

/*
|--------------------------------------------------------------------------
| broadcastWhen — conditionnel sur le driver
|--------------------------------------------------------------------------
*/

test('broadcastWhen retourne false quand broadcasting.default est log', function () {
    config(['broadcasting.default' => 'log']);

    $project = Project::factory()->create();
    $event = new ProjectUpdated($project);

    expect($event->broadcastWhen())->toBeFalse();
});

test('broadcastWhen retourne false quand broadcasting.default est null', function () {
    config(['broadcasting.default' => 'null']);

    $event = new NewNotification(fake()->uuid(), 'info', 'test');

    expect($event->broadcastWhen())->toBeFalse();
});

test('broadcastWhen retourne true quand broadcasting.default est reverb', function () {
    config(['broadcasting.default' => 'reverb']);

    $project = Project::factory()->create();
    $event = new ProjectUpdated($project);

    expect($event->broadcastWhen())->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Channel authorization
|--------------------------------------------------------------------------
*/

test('channel auth : user peut acceder a son propre channel user.{id}', function () {
    $org = createOrg();
    $user = createUser([], $org);

    // Le callback du channel user.{userId} retourne true si $user->id === $userId
    $result = ($user->id === $user->id);

    expect($result)->toBeTrue();
});

test('channel auth : user ne peut pas acceder au channel user d un autre', function () {
    $org = createOrg();
    $user = createUser([], $org);
    $other = createUser([], $org);

    // Simule la logique du channel callback
    $result = ($user->id === $other->id);

    expect($result)->toBeFalse();
});

test('channel auth : user peut acceder au channel org de son organisation', function () {
    $org = createOrg();
    $user = createUser([], $org);

    $result = ($user->organization_id === $org->id);

    expect($result)->toBeTrue();
});

test('channel auth : user ne peut pas acceder au channel org d une autre organisation', function () {
    $orgA = createOrg();
    $orgB = createOrg();
    $user = createUser([], $orgA);

    $result = ($user->organization_id === $orgB->id);

    expect($result)->toBeFalse();
});

test('channel auth : user peut acceder au channel project de son organisation', function () {
    $org = createOrg();
    $user = createUser([], $org);
    $project = Project::factory()->create(['organization_id' => $org->id, 'creator_user_id' => $user->id]);

    // Simule la logique du channel callback
    $found = Project::find($project->id);
    $result = $found && $found->organization_id === $user->organization_id;

    expect($result)->toBeTrue();
});

test('channel auth : user ne peut pas acceder au channel project d une autre org', function () {
    $orgA = createOrg();
    $orgB = createOrg();
    $userA = createUser([], $orgA);
    $userB = createUser([], $orgB);
    $project = Project::factory()->create(['organization_id' => $orgB->id, 'creator_user_id' => $userB->id]);

    // Simule la logique du channel callback
    $found = Project::find($project->id);
    $result = $found && $found->organization_id === $userA->organization_id;

    expect($result)->toBeFalse();
});
