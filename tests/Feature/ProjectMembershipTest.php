<?php

use App\Enums\AccountType;
use App\Models\Project;
use App\Models\User;

test('addMember attaches a user to the project with the given role', function () {
    $org = createOrg();
    $owner = createOrgAdmin($org);
    $member = createOrgUser($org);

    loginAs($owner);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $owner->id,
    ]);

    $project->addMember($member, 'editor');

    expect($project->members()->where('user_id', $member->id)->exists())->toBeTrue();
    expect($project->members()->where('user_id', $member->id)->first()->pivot->role)->toBe('editor');
});

test('addMember does not create duplicate when user is already a member', function () {
    $org = createOrg();
    $owner = createOrgAdmin($org);
    $member = createOrgUser($org);

    loginAs($owner);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $owner->id,
    ]);

    $project->addMember($member, 'member');
    $project->addMember($member, 'member');

    $count = $project->members()->where('user_id', $member->id)->count();
    expect($count)->toBe(1);
});

test('removeMember detaches a user from the project', function () {
    $org = createOrg();
    $owner = createOrgAdmin($org);
    $member = createOrgUser($org);

    loginAs($owner);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $owner->id,
    ]);

    $project->addMember($member);
    $project->removeMember($member);

    expect($project->members()->where('user_id', $member->id)->exists())->toBeFalse();
});

test('creator is auto-added as project member on creation', function () {
    $org = createOrg();
    $user = createOrgAdmin($org);

    loginAs($user);

    $project = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    expect($project->members()->where('user_id', $user->id)->exists())->toBeTrue();
    expect($project->members()->where('user_id', $user->id)->first()->pivot->role)->toBe('creator');
});

test('visibleTo returns all projects for ORG_ADMIN', function () {
    $org = createOrg();
    $admin = createOrgAdmin($org);
    $otherUser = createOrgUser($org);

    loginAs($admin);

    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $otherUser->id,
    ]);

    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $admin->id,
    ]);

    $visible = Project::visibleTo($admin)->get();

    expect($visible)->toHaveCount(2);
});

test('visibleTo returns only own and member projects for ORG_USER', function () {
    $org = createOrg();
    $user = createOrgUser($org);
    $otherUser = createOrgUser($org);

    loginAs($user);

    // Project created by user (visible)
    $ownProject = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $user->id,
    ]);

    // Project where user is a member (visible)
    $memberProject = Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $otherUser->id,
    ]);
    $memberProject->addMember($user, 'member');

    // Project user has no access to (not visible)
    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $otherUser->id,
    ]);

    $visible = Project::visibleTo($user)->get();

    expect($visible)->toHaveCount(2);
    expect($visible->pluck('id')->toArray())
        ->toContain($ownProject->id)
        ->toContain($memberProject->id);
});

test('visibleTo excludes projects where ORG_USER is not creator nor member', function () {
    $org = createOrg();
    $user = createOrgUser($org);
    $otherUser = createOrgUser($org);

    loginAs($user);

    Project::factory()->create([
        'organization_id' => $org->id,
        'creator_user_id' => $otherUser->id,
    ]);

    $visible = Project::visibleTo($user)->get();

    expect($visible)->toHaveCount(0);
});
