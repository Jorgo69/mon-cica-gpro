<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\HasMeta;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
        'owner_user_id',
        'logo_path',
        'website',
        'contact_email',
        'contact_phone',
        'description',
        'status',
        'plan',
        'plan_activated_at',
        'plan_expires_at',
        'plan_notes',
        'meta',
    ];

    protected $attributes = [
        'status' => 'trial',
    ];

    protected $casts = [
        'status' => \App\Enums\OrganizationStatus::class,
        'plan' => \App\Enums\Plan::class,
        'plan_activated_at' => 'datetime',
        'plan_expires_at' => 'datetime',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::orderedUuid());
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function isOwner(?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        return $user && $this->owner_user_id === $user->id;
    }

    public function currentPlan(): \App\Enums\Plan
    {
        return $this->plan ?? \App\Enums\Plan::FREE;
    }

    public function isPlanActive(): bool
    {
        $plan = $this->currentPlan();

        if ($plan === \App\Enums\Plan::FREE) {
            return true;
        }

        if ($this->plan_expires_at && $this->plan_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function canCreateProject(): bool
    {
        if (isSelfHosted()) return true;

        $max = $this->currentPlan()->maxProjects();
        if ($max === -1) return true;
        return $this->projects()->count() < $max;
    }

    public function canAddMember(): bool
    {
        if (isSelfHosted()) return true;

        $max = $this->currentPlan()->maxMembers();
        if ($max === -1) return true;
        return $this->users()->count() < $max;
    }

    public function hasFeature(string $feature): bool
    {
        if (isSelfHosted()) return true;

        return $this->currentPlan()->hasFeature($feature);
    }

    public function aiConfig()
    {
        return $this->morphOne(AiConfig::class, 'configurable');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }

    public function hasLogo(): bool
    {
        return !empty($this->logo_path);
    }
}
