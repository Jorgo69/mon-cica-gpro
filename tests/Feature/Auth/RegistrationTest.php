<?php

use App\Livewire\Auth\RegisterLivewire;
use App\Models\User;
use Livewire\Livewire;

test('new users can register', function () {
    Livewire::test(RegisterLivewire::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'Password123!')
        ->set('password_confirmation', 'Password123!')
        ->set('accept_terms', true)
        ->call('register')
        ->assertRedirect(route('onboarding'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('registration requires valid email', function () {
    Livewire::test(RegisterLivewire::class)
        ->set('name', 'Test User')
        ->set('email', 'not-an-email')
        ->set('password', 'Password123!')
        ->set('password_confirmation', 'Password123!')
        ->call('register')
        ->assertHasErrors('email');

    $this->assertGuest();
});

test('registration requires password confirmation', function () {
    Livewire::test(RegisterLivewire::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'Password123!')
        ->set('password_confirmation', 'DifferentPassword!')
        ->call('register')
        ->assertHasErrors('password');

    $this->assertGuest();
});

test('registration requires unique email', function () {
    createUser(['email' => 'existing@example.com']);

    Livewire::test(RegisterLivewire::class)
        ->set('name', 'Test User')
        ->set('email', 'existing@example.com')
        ->set('password', 'Password123!')
        ->set('password_confirmation', 'Password123!')
        ->call('register')
        ->assertHasErrors('email');

    $this->assertGuest();
});
