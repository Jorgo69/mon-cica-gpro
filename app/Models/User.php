<?php

namespace App\Models;

use App\Enums\AccountType;
use App\Traits\HasUuid;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, LogsActivity, HasUuid;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'telephone',
        'numero_identification',
        'country',
        'location',
        'account_type',
        'department_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'account_type'      => AccountType::class,
        'location'          => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['password', 'remember_token'])
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(\Spatie\Activitylog\Models\Activity $activity, string $eventName): void
    {
        $activity->organization_id = session('current_organization_id');
    }

    // Relations

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class)
            ->withPivot('role', 'status', 'joined_at')
            ->withTimestamps();
    }

    public function currentOrganization(): ?Organization
    {
        $orgId = session('current_organization_id');
        return $orgId ? $this->organizations()->find($orgId) : null;
    }

    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'creator_user_id');
    }

    public function uploadedDocuments()
    {
        return $this->hasMany(ProjectDocument::class, 'creator_user_id');
    }

    public function responsibleActivities()
    {
        return $this->hasMany(Activity::class, 'responsible_user_id');
    }

    public function responsibleResources()
    {
        return $this->hasMany(Resource::class, 'responsible_user_id');
    }

    public function projectUpdates()
    {
        return $this->hasMany(ProjectUpdate::class, 'creator_user_id');
    }
}
