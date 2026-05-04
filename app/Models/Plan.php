<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'currency',
        'billing_period',
        'max_projects',
        'max_members',
        'features',
        'is_default',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'max_projects' => 'integer',
        'max_members' => 'integer',
        'features' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // ─── Helpers ────────────────────────────────────

    public function maxProjects(): int
    {
        return $this->max_projects;
    }

    public function maxMembers(): int
    {
        return $this->max_members;
    }

    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? []);
    }

    public function isUnlimited(): bool
    {
        return $this->max_projects === -1 && $this->max_members === -1;
    }

    public function formattedPrice(): string
    {
        if ($this->price === 0) {
            return __('plans.free_price');
        }

        return number_format($this->price, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function label(): string
    {
        return $this->name;
    }

    public function color(): string
    {
        return match ($this->slug) {
            'free' => 'text-muted',
            'pro' => 'text-accent',
            'enterprise' => 'text-amber-500',
            default => 'text-heading',
        };
    }

    public function badgeColor(): string
    {
        return match ($this->slug) {
            'free' => 'gray',
            'pro' => 'blue',
            'enterprise' => 'amber',
            default => 'gray',
        };
    }

    // ─── Scopes ─────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // ─── Static helpers ─────────────────────────────

    public static function defaultPlan(): self
    {
        return static::where('is_default', true)->first()
            ?? static::where('slug', 'free')->first()
            ?? static::ordered()->first();
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }
}
