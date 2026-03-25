<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Multitenantable;
use App\Enums\ProjectStatus;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Project extends Model
{
    use HasFactory, SoftDeletes, Multitenantable, LogsActivity, HasUuid;

    protected $fillable = [
        'organization_id',
        'project_type_id',
        'creator_user_id',
        'project_code',
        'title',
        'short_title',
        'description',
        'problem_analysis',
        'strategy',
        'justification',
        'context_description',
        'ai_analysis_result',
        'general_objectives',
        'status',
        'start_date',
        'end_date',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected $casts = [
        'status'             => ProjectStatus::class,
        'start_date'         => 'date',
        'end_date'           => 'date',
        'general_objectives' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function tapActivity(\Spatie\Activitylog\Models\Activity $activity, string $eventName): void
    {
        $activity->organization_id = $this->organization_id;
    }

    // Relations

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function logicalFramework()
    {
        return $this->hasOne(LogicalFramework::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function updates()
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    // Méthodes métier

    public function getAllActivities(): Collection
    {
        $this->loadMissing('logicalFramework.specificObjectives.results.activities');

        if (!$this->logicalFramework) {
            return collect();
        }

        return $this->logicalFramework->specificObjectives
            ->flatMap(fn ($obj) => $obj->results)
            ->flatMap(fn ($result) => $result->activities);
    }

    public function calculateProgress(): float
    {
        $activities = $this->getAllActivities();

        if ($activities->isEmpty()) {
            return 0.0;
        }

        return round($activities->sum->calculateProgress() / $activities->count(), 2);
    }
}
