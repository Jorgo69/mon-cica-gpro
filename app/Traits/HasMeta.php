<?php

namespace App\Traits;

use Illuminate\Support\Arr;

/**
 * Trait HasMeta — Ajoute un stockage JSON flexible a n'importe quel model Eloquent.
 *
 * Prerequis : le model doit avoir une colonne `meta` (JSON, default '{}')
 *             et le cast `'meta' => 'array'` dans $casts.
 *
 * Usage :
 *   $model->getMeta('theme');                   // 'dark'
 *   $model->setMeta('locale', 'fr');            // set + save
 *   $model->setMeta(['a' => 1, 'b' => 2]);     // bulk set
 *   $model->forgetMeta('old_key');              // remove + save
 *   $model->hasMeta('theme');                   // true/false
 *   $model->allMeta();                          // ['theme' => 'dark', ...]
 *
 * Dot notation supportee : $model->getMeta('notifications.email', true)
 */
trait HasMeta
{
    public function getMeta(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->meta ?? [], $key, $default);
    }

    public function setMeta(string|array $key, mixed $value = null): static
    {
        $meta = $this->meta ?? [];

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Arr::set($meta, $k, $v);
            }
        } else {
            Arr::set($meta, $key, $value);
        }

        $this->meta = $meta;
        $this->saveQuietly();

        return $this;
    }

    public function forgetMeta(string $key): static
    {
        $meta = $this->meta ?? [];
        Arr::forget($meta, $key);

        $this->meta = $meta;
        $this->saveQuietly();

        return $this;
    }

    public function hasMeta(string $key): bool
    {
        return Arr::has($this->meta ?? [], $key);
    }

    public function allMeta(): array
    {
        return $this->meta ?? [];
    }

    public function mergeMeta(array $values): static
    {
        $meta = $this->meta ?? [];

        foreach ($values as $k => $v) {
            Arr::set($meta, $k, $v);
        }

        $this->meta = $meta;
        $this->saveQuietly();

        return $this;
    }
}
