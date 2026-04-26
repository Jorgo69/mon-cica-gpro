<?php

namespace App\Services;

use Illuminate\Support\Arr;

class UserMeta
{
    /**
     * Get a meta value (dot notation supported).
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $user = auth()->user();
        if (!$user) {
            return $default;
        }

        return Arr::get($user->meta ?? [], $key, $default);
    }

    /**
     * Set one or many meta values (dot notation supported).
     */
    public static function set(string|array $key, mixed $value = null): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        $meta = $user->meta ?? [];

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Arr::set($meta, $k, $v);
            }
        } else {
            Arr::set($meta, $key, $value);
        }

        $user->meta = $meta;
        $user->saveQuietly();
    }

    /**
     * Remove a meta key (dot notation supported).
     */
    public static function forget(string $key): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        $meta = $user->meta ?? [];
        Arr::forget($meta, $key);

        $user->meta = $meta;
        $user->saveQuietly();
    }

    /**
     * Get all meta as array.
     */
    public static function all(): array
    {
        return auth()->user()?->meta ?? [];
    }

    /**
     * Check if a meta key exists.
     */
    public static function has(string $key): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return Arr::has($user->meta ?? [], $key);
    }
}
