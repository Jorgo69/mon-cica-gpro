<?php

namespace App\Models;

use App\Enums\AccountType;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, \App\Traits\Multitenantable, \Spatie\Activitylog\Traits\LogsActivity, \App\Traits\HasMeta, \App\Traits\HasNotificationPreferences;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'organization_id',
        'is_independent',
        'sexe',
        'telephone',
        'numero_identification',
        'pays',
        'ville',
        'department',
        'password',
        'plan',
        'plan_activated_at',
        'plan_expires_at',
        'meta',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => AccountType::class,
        'plan_activated_at' => 'datetime',
        'plan_expires_at' => 'datetime',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::orderedUuid();
        });

        // Protéger contre les strings vides dans role (le cast enum plante sur "")
        static::saving(function ($model) {
            if ($model->role === '' || $model->getRawOriginal('role') === '') {
                $model->role = null;
            }
        });
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['password', 'remember_token'])
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(\Spatie\Activitylog\Models\Activity $activity, string $eventName)
    {
        $activity->organization_id = $this->organization_id ?? auth()->user()?->organization_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    // Relationship removed in favor of Enum-based roles as requested by the user
    // public function role(): BelongsTo
    // {
    //     return $this->belongsTo(Role::class, 'role_id', 'id');
    // }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function planRelation()
    {
        return $this->belongsTo(\App\Models\Plan::class, 'plan_id');
    }

    /**
     * Get the effective plan for this user.
     * For org members: uses the organization's plan.
     * For independents: uses their own plan.
     */
    public function effectivePlan(): \App\Models\Plan
    {
        if ($this->role === AccountType::INDEPENDENT || !$this->organization_id) {
            return $this->planRelation ?? \App\Models\Plan::defaultPlan();
        }

        return $this->organization?->currentPlan() ?? \App\Models\Plan::defaultPlan();
    }

    public function isPlanActive(): bool
    {
        if (isSelfHosted()) return true;

        if ($this->role === AccountType::INDEPENDENT || !$this->organization_id) {
            $plan = $this->planRelation ?? \App\Models\Plan::defaultPlan();
            if ($plan->is_default || $plan->price === 0) return true;
            return !$this->plan_expires_at || !$this->plan_expires_at->isPast();
        }

        return $this->organization?->isPlanActive() ?? true;
    }

    public function hasFeature(string $feature): bool
    {
        if (isSelfHosted()) return true;

        return $this->effectivePlan()->hasFeature($feature);
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function fcmTokens(): HasMany
    {
        return $this->hasMany(FcmToken::class);
    }

    public function aiConfig()
    {
        return $this->morphOne(AiConfig::class, 'configurable');
    }

    public function routeNotificationForFcm(): array
    {
        return $this->fcmTokens()->pluck('token')->toArray();
    }

    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'creator_user_id', 'id');
    }
    public function uploadedDocuments(): HasMany
    {
        return $this->hasMany(ProjectDocument::class, 'creator_user_id', 'id');
    }
    public function responsibleActivities(): HasMany
    {
        return $this->hasMany(Activity::class, 'responsible_user_id', 'id');
    }
    public function responsibleResources(): HasMany
    {
        return $this->hasMany(Resource::class, 'responsible_user_id', 'id');
    }
    public function responsibleBudgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'responsible_user_id', 'id');
    }
    public function progressUpdates(): HasMany
    {
        return $this->hasMany(ProgressTracker::class, 'creator_user_id', 'id');
    }
    public function qualitativeEvaluations(): HasMany
    {
        return $this->hasMany(QualitativeEvaluation::class, 'evaluator_id', 'id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
