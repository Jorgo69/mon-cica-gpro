<?php

use App\Livewire\Auth\LoginLivewire;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

test('guests can see login page', function () {
    $this->get('/login')->assertStatus(200);
});

test('guests can see register page', function () {
    $this->get('/register')->assertStatus(200);
});

test('users can authenticate with valid credentials', function () {
    $user = createUser(['password' => bcrypt('secret-password')]);

    Livewire::test(LoginLivewire::class)
        ->set('email', $user->email)
        ->set('password', 'secret-password')
        ->call('login')
        ->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});

test('users cannot authenticate with wrong password', function () {
    $user = createUser();

    Livewire::test(LoginLivewire::class)
        ->set('email', $user->email)
        ->set('password', 'wrong-password')
        ->call('login')
        ->assertHasErrors('email')
        ->assertNoRedirect();

    $this->assertGuest();
});

test('authenticated users are redirected from login', function () {
    $user = createUser();

    $this->actingAs($user)
        ->get('/login')
        ->assertRedirect();
});

test('users can logout', function () {
    $user = createUser();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/');

    $this->assertGuest();
});

/*
|--------------------------------------------------------------------------
| Rate limiting
|--------------------------------------------------------------------------
*/

test('login rate limiting bloque apres 5 tentatives echouees', function () {
    $user = createUser(['password' => bcrypt('secret-password')]);

    // 5 tentatives echouees
    for ($i = 0; $i < 5; $i++) {
        Livewire::test(LoginLivewire::class)
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->call('login');
    }

    // 6eme tentative (meme avec le bon mot de passe) → bloquee
    Livewire::test(LoginLivewire::class)
        ->set('email', $user->email)
        ->set('password', 'secret-password')
        ->call('login')
        ->assertHasErrors('email')
        ->assertNoRedirect();

    $this->assertGuest();
});

test('login rate limiting se reinitialise apres un login reussi', function () {
    $user = createUser(['password' => bcrypt('secret-password')]);

    // 3 tentatives echouees
    for ($i = 0; $i < 3; $i++) {
        Livewire::test(LoginLivewire::class)
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->call('login');
    }

    // Login reussi
    Livewire::test(LoginLivewire::class)
        ->set('email', $user->email)
        ->set('password', 'secret-password')
        ->call('login')
        ->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});
