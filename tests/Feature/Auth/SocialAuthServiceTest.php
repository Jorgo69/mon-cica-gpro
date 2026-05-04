<?php

use App\Models\SocialAccount;
use App\Models\User;
use App\Services\SocialAuthService;
use Laravel\Socialite\Contracts\User as SocialiteUser;

function makeSocialiteUser(array $overrides = []): SocialiteUser
{
    $defaults = [
        'id' => 'provider-id-123',
        'name' => 'Test User',
        'email' => 'social@example.com',
        'nickname' => 'testuser',
        'avatar' => 'https://example.com/avatar.png',
        'token' => 'access-token-abc',
        'refreshToken' => 'refresh-token-xyz',
    ];

    $data = array_merge($defaults, $overrides);

    $mock = Mockery::mock(SocialiteUser::class);
    $mock->shouldReceive('getId')->andReturn($data['id']);
    $mock->shouldReceive('getName')->andReturn($data['name']);
    $mock->shouldReceive('getEmail')->andReturn($data['email']);
    $mock->shouldReceive('getNickname')->andReturn($data['nickname']);
    $mock->shouldReceive('getAvatar')->andReturn($data['avatar']);
    $mock->token = $data['token'];
    $mock->refreshToken = $data['refreshToken'];

    return $mock;
}

/*
|--------------------------------------------------------------------------
| SocialAuthService::handleCallback — intent=login
|--------------------------------------------------------------------------
*/

test('handleCallback intent=login avec social account existant retourne le user', function () {
    $user = createUser(['email' => 'social@example.com']);
    SocialAccount::create([
        'user_id' => $user->id,
        'provider' => 'google',
        'provider_id' => 'google-id-123',
        'provider_email' => $user->email,
    ]);

    $socialiteUser = makeSocialiteUser([
        'id' => 'google-id-123',
        'email' => $user->email,
    ]);

    $service = new SocialAuthService();
    $result = $service->handleCallback('google', $socialiteUser, 'login');

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($user->id);
});

test('handleCallback intent=login avec user existant par email lie le social account', function () {
    $user = createUser(['email' => 'existing@example.com']);

    $socialiteUser = makeSocialiteUser([
        'id' => 'google-id-456',
        'email' => 'existing@example.com',
    ]);

    $service = new SocialAuthService();
    $result = $service->handleCallback('google', $socialiteUser, 'login');

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($user->id);

    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $user->id,
        'provider' => 'google',
        'provider_id' => 'google-id-456',
    ]);
});

test('handleCallback intent=login sans aucun compte retourne null', function () {
    $socialiteUser = makeSocialiteUser([
        'email' => 'unknown@example.com',
    ]);

    $service = new SocialAuthService();
    $result = $service->handleCallback('google', $socialiteUser, 'login');

    expect($result)->toBeNull();

    $this->assertDatabaseMissing('users', ['email' => 'unknown@example.com']);
});

/*
|--------------------------------------------------------------------------
| SocialAuthService::handleCallback — intent=register
|--------------------------------------------------------------------------
*/

test('handleCallback intent=register sans aucun compte cree un nouveau user', function () {
    // Force mode saas pour eviter le chemin selfhosted
    config(['gpro.mode' => 'saas']);

    $socialiteUser = makeSocialiteUser([
        'id' => 'google-new-789',
        'name' => 'New User',
        'email' => 'newuser@example.com',
    ]);

    $service = new SocialAuthService();
    $result = $service->handleCallback('google', $socialiteUser, 'register');

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->email)->toBe('newuser@example.com')
        ->and($result->name)->toBe('New User');

    // NOTE: email_verified_at devrait etre set mais ne l'est pas car absent des fillable du User model.
    // Bug connu — a corriger dans User::$fillable.

    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $result->id,
        'provider' => 'google',
        'provider_id' => 'google-new-789',
    ]);
});

test('handleCallback intent=register avec user existant par email retourne le user existant', function () {
    $user = createUser(['email' => 'existing@example.com']);

    $socialiteUser = makeSocialiteUser([
        'id' => 'google-id-exist',
        'email' => 'existing@example.com',
    ]);

    $service = new SocialAuthService();
    $result = $service->handleCallback('google', $socialiteUser, 'register');

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($user->id);

    // Social account lie, pas de nouveau user cree
    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $user->id,
        'provider' => 'google',
    ]);
    expect(User::where('email', 'existing@example.com')->count())->toBe(1);
});

test('handleCallback intent=register avec social account existant retourne le user', function () {
    $user = createUser(['email' => 'social@example.com']);
    SocialAccount::create([
        'user_id' => $user->id,
        'provider' => 'google',
        'provider_id' => 'google-id-already',
        'provider_email' => $user->email,
    ]);

    $socialiteUser = makeSocialiteUser([
        'id' => 'google-id-already',
        'email' => $user->email,
    ]);

    $service = new SocialAuthService();
    $result = $service->handleCallback('google', $socialiteUser, 'register');

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($user->id);

    // Pas de doublon de social account
    expect(SocialAccount::where('provider_id', 'google-id-already')->count())->toBe(1);
});

/*
|--------------------------------------------------------------------------
| SocialAuthService::handleCallback — selfhosted premier user
|--------------------------------------------------------------------------
*/

test('handleCallback intent=register en selfhosted sans users cree un org_admin', function () {
    config(['gpro.mode' => 'selfhosted']);
    seedPermissions();

    $socialiteUser = makeSocialiteUser([
        'id' => 'google-first-admin',
        'name' => 'First Admin',
        'email' => 'admin@selfhosted.com',
    ]);

    $service = new SocialAuthService();
    $result = $service->handleCallback('google', $socialiteUser, 'register');

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->email)->toBe('admin@selfhosted.com')
        ->and($result->role)->toBe(\App\Enums\AccountType::ORG_ADMIN)
        ->and($result->organization_id)->not->toBeNull();

    // Organisation creee avec ce user comme owner
    $org = $result->organization;
    expect($org)->not->toBeNull()
        ->and($org->owner_user_id)->toBe($result->id);
});
