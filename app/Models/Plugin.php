<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plugin extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'slug',
        'name',
        'version',
        'author',
        'description',
        'provider_class',
        'path',
        'hooks',
        'permissions',
        'settings',
        'is_active',
        'installed_at',
    ];

    protected $casts = [
        'hooks' => 'array',
        'permissions' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'installed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    // --- Relations ---

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_plugin')
            ->withPivot(['is_enabled', 'settings'])
            ->withTimestamps();
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // --- Helpers ---

    public function isEnabledForOrg(string $orgId): bool
    {
        return $this->organizations()
            ->where('organization_id', $orgId)
            ->wherePivot('is_enabled', true)
            ->exists();
    }

    public function enableForOrg(string $orgId): void
    {
        $this->organizations()->syncWithoutDetaching([
            $orgId => ['is_enabled' => true],
        ]);
    }

    public function disableForOrg(string $orgId): void
    {
        $this->organizations()->syncWithoutDetaching([
            $orgId => ['is_enabled' => false],
        ]);
    }

    public function listensTo(string $hook): bool
    {
        return in_array($hook, $this->hooks ?? []);
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }
}
