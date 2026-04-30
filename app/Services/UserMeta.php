<?php

namespace App\Services;

/**
 * UserMeta — Wrapper statique pour acceder aux meta de l'utilisateur connecte.
 *
 * Delegue au trait HasMeta sur le model User.
 * Utiliser directement dans les controllers, middleware, vues Blade.
 *
 * Usage :
 *   UserMeta::get('theme', 'light')
 *   UserMeta::set('locale', 'fr')
 *   UserMeta::set(['theme' => 'dark', 'density' => 'comfortable'])
 *   UserMeta::forget('old_key')
 *   UserMeta::has('theme')
 *   UserMeta::all()
 */
class UserMeta
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return auth()->user()?->getMeta($key, $default) ?? $default;
    }

    public static function set(string|array $key, mixed $value = null): void
    {
        auth()->user()?->setMeta($key, $value);
    }

    public static function forget(string $key): void
    {
        auth()->user()?->forgetMeta($key);
    }

    public static function has(string $key): bool
    {
        return auth()->user()?->hasMeta($key) ?? false;
    }

    public static function all(): array
    {
        return auth()->user()?->allMeta() ?? [];
    }
}
