<?php

use App\Actions\Invitation\SendInvitationAction;
use App\Enums\InvitationStatus;
use App\Models\Invitation;
use App\Models\Organization;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    seedPermissions();
});

test('org_admin can send invitation', function () {
    Notification::fake();

    $org = createOrg();
    $admin = createOrgAdmin($org);
    $admin->assignRole('ORG_ADMIN');

    loginAs($admin);

    $action = new SendInvitationAction();
    $invitation = $action->execute([
        'email' => 'newmember@example.com',
        'role' => 'org_user',
        'spatie_role' => 'MEMBER',
        'organization_id' => $org->id,
    ]);

    expect($invitation)->toBeInstanceOf(Invitation::class);
    expect($invitation->email)->toBe('newmember@example.com');
    expect($invitation->organization_id)->toBe($org->id);
});

test('invitation creates a record with pending status', function () {
    Notification::fake();

    $org = createOrg();
    $admin = createOrgAdmin($org);

    loginAs($admin);

    $action = new SendInvitationAction();
    $invitation = $action->execute([
        'email' => 'pending@example.com',
        'role' => 'org_user',
        'spatie_role' => 'MEMBER',
        'organization_id' => $org->id,
    ]);

    expect($invitation->status)->toBe(InvitationStatus::PENDING);
    expect($invitation->token)->not->toBeEmpty();
    expect($invitation->expires_at)->not->toBeNull();

    $this->assertDatabaseHas('invitations', [
        'email' => 'pending@example.com',
        'status' => InvitationStatus::PENDING->value,
    ]);
});

test('invitation link works for valid token', function () {
    Notification::fake();

    $org = createOrg();
    $admin = createOrgAdmin($org);

    loginAs($admin);

    $action = new SendInvitationAction();
    $invitation = $action->execute([
        'email' => 'visitor@example.com',
        'role' => 'org_user',
        'spatie_role' => 'MEMBER',
        'organization_id' => $org->id,
    ]);

    // Logout to test as guest
    auth()->logout();

    $response = $this->get(route('invitation.accept', ['token' => $invitation->token]));

    // Guest with no existing account should be redirected to register
    $response->assertRedirect();
    $response->assertSessionHas('invitation_token', $invitation->token);
});

test('invitation link with invalid token redirects to login with error', function () {
    $response = $this->get(route('invitation.accept', ['token' => 'invalid-token-xyz']));

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error');
});

test('duplicate pending invitation for same email is rejected', function () {
    Notification::fake();

    $org = createOrg();
    $admin = createOrgAdmin($org);

    loginAs($admin);

    $action = new SendInvitationAction();
    $action->execute([
        'email' => 'duplicate@example.com',
        'role' => 'org_user',
        'spatie_role' => 'MEMBER',
        'organization_id' => $org->id,
    ]);

    expect(fn () => $action->execute([
        'email' => 'duplicate@example.com',
        'role' => 'org_user',
        'spatie_role' => 'MEMBER',
        'organization_id' => $org->id,
    ]))->toThrow(\Illuminate\Validation\ValidationException::class);
});
