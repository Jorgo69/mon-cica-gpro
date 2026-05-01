<?php

use App\Models\User;
use App\Services\UserMeta;

/*
|--------------------------------------------------------------------------
| HasMeta trait (via User model)
|--------------------------------------------------------------------------
*/

test('un utilisateur peut definir et lire une meta', function () {
    $user = createUser();

    $user->setMeta('theme', 'dark');

    expect($user->getMeta('theme'))->toBe('dark');
});

test('getMeta retourne la valeur par defaut si la cle n existe pas', function () {
    $user = createUser();

    expect($user->getMeta('nonexistent', 'fallback'))->toBe('fallback');
});

test('getMeta retourne null par defaut si aucune valeur par defaut', function () {
    $user = createUser();

    expect($user->getMeta('nonexistent'))->toBeNull();
});

test('hasMeta retourne true si la cle existe', function () {
    $user = createUser();

    $user->setMeta('locale', 'fr');

    expect($user->hasMeta('locale'))->toBeTrue();
});

test('hasMeta retourne false si la cle n existe pas', function () {
    $user = createUser();

    expect($user->hasMeta('unknown_key'))->toBeFalse();
});

test('forgetMeta supprime une cle', function () {
    $user = createUser();

    $user->setMeta('temp', 'value');
    expect($user->hasMeta('temp'))->toBeTrue();

    $user->forgetMeta('temp');
    expect($user->hasMeta('temp'))->toBeFalse();
});

test('setMeta supporte le bulk set avec un tableau', function () {
    $user = createUser();

    $user->setMeta(['theme' => 'dark', 'density' => 'comfortable']);

    expect($user->getMeta('theme'))->toBe('dark');
    expect($user->getMeta('density'))->toBe('comfortable');
});

test('setMeta supporte la dot notation pour les cles imbriquees', function () {
    $user = createUser();

    $user->setMeta('notifications.email', true);
    $user->setMeta('notifications.push', false);

    expect($user->getMeta('notifications.email'))->toBeTrue();
    expect($user->getMeta('notifications.push'))->toBeFalse();
});

test('hasMeta supporte la dot notation', function () {
    $user = createUser();

    $user->setMeta('preferences.sidebar', 'collapsed');

    expect($user->hasMeta('preferences.sidebar'))->toBeTrue();
    expect($user->hasMeta('preferences.other'))->toBeFalse();
});

test('forgetMeta supporte la dot notation', function () {
    $user = createUser();

    $user->setMeta('ui.font_size', 14);
    $user->forgetMeta('ui.font_size');

    expect($user->hasMeta('ui.font_size'))->toBeFalse();
});

test('allMeta retourne toutes les meta', function () {
    $user = createUser();

    $user->setMeta(['a' => 1, 'b' => 2]);

    $all = $user->allMeta();
    expect($all)->toHaveKey('a', 1);
    expect($all)->toHaveKey('b', 2);
});

test('allMeta retourne un tableau vide si aucune meta', function () {
    $user = createUser();

    expect($user->allMeta())->toBeArray()->toBeEmpty();
});

test('setMeta persiste en base de donnees', function () {
    $user = createUser();

    $user->setMeta('persisted', 'yes');

    $fresh = User::find($user->id);
    expect($fresh->getMeta('persisted'))->toBe('yes');
});

test('mergeMeta fusionne sans ecraser les cles existantes non mentionnees', function () {
    $user = createUser();

    $user->setMeta('existing', 'keep');
    $user->mergeMeta(['new_key' => 'added']);

    expect($user->getMeta('existing'))->toBe('keep');
    expect($user->getMeta('new_key'))->toBe('added');
});

/*
|--------------------------------------------------------------------------
| UserMeta static helper (needs auth)
|--------------------------------------------------------------------------
*/

test('UserMeta::get retourne la meta de l utilisateur connecte', function () {
    $user = createUser();
    $user->setMeta('theme', 'dark');

    loginAs($user);

    expect(UserMeta::get('theme'))->toBe('dark');
});

test('UserMeta::set definit une meta pour l utilisateur connecte', function () {
    $user = createUser();
    loginAs($user);

    UserMeta::set('locale', 'en');

    expect($user->fresh()->getMeta('locale'))->toBe('en');
});

test('UserMeta::has retourne true si la meta existe', function () {
    $user = createUser();
    $user->setMeta('key', 'value');
    loginAs($user);

    expect(UserMeta::has('key'))->toBeTrue();
});

test('UserMeta::forget supprime la meta', function () {
    $user = createUser();
    $user->setMeta('disposable', 'value');
    loginAs($user);

    UserMeta::forget('disposable');

    expect(UserMeta::has('disposable'))->toBeFalse();
});

test('UserMeta::all retourne toutes les meta', function () {
    $user = createUser();
    $user->setMeta(['x' => 1, 'y' => 2]);
    loginAs($user);

    expect(UserMeta::all())->toHaveKey('x', 1)->toHaveKey('y', 2);
});

test('UserMeta retourne les valeurs par defaut quand personne n est connecte', function () {
    // No authenticated user
    expect(UserMeta::get('anything', 'default'))->toBe('default');
    expect(UserMeta::has('anything'))->toBeFalse();
    expect(UserMeta::all())->toBeEmpty();
});
