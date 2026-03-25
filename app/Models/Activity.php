<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Enums\ActivityStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Activity extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasUuid;

    protected $fillable = [
        'result_id',
        'project_id',
        'parent_id',
        'creator_user_id',
        'responsible_user_id',
        'description',
        'justification',
        'start_date',
        'end_date',
        'status',
        'progress_percentage',
        'is_milestone',
        'order',
    ];

    protected $casts = [
        'status'              => ActivityStatus::class,
        'start_date'          => 'date',
        'end_date'            => 'date',
        'is_milestone'        => 'boolean',
        'progress_percentage' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function tapActivity(\Spatie\Activitylog\Models\Activity $activity, string $eventName): void
    {
        $activity->organization_id = $this->project?->organization_id;
    }

    // Relations

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function parent()
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id')->orderBy('order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function updates()
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    // Méthodes métier

    public function getPlannedProgressPercentage(): float
    {
        if (!$this->start_date || !$this->end_date) {
            return 0.0;
        }

        $today = now();

        if ($today->lt($this->start_date)) return 0.0;
        if ($today->gte($this->end_date))  return 100.0;

        $total   = $this->start_date->diffInDays($this->end_date);
        $elapsed = $this->start_date->diffInDays($today);

        return $total > 0 ? round(($elapsed / $total) * 100, 2) : 100.0;
    }

    public function calculateProgress(): float
    {
        $children = $this->children;

        if ($children->isEmpty()) {
            return $this->status === ActivityStatus::COMPLETED
                ? 100.0
                : (float) ($this->progress_percentage ?? 0);
        }

        $weights = [
            ActivityStatus::DRAFT->value     => 0,
            ActivityStatus::ABANDONED->value => 0,
            ActivityStatus::STOPPED->value   => 0,
            ActivityStatus::PENDING->value   => 0,
            ActivityStatus::ONGOING->value   => 50,
            ActivityStatus::SUSPENDED->value => 25,
            ActivityStatus::COMPLETED->value => 100,
            ActivityStatus::OVERDUE->value   => 10,
        ];

        $total = $children->sum(function ($child) use ($weights) {
            $value = $child->status instanceof ActivityStatus
                ? $child->status->value
                : $child->status;

            return $weights[$value] ?? (float) ($child->progress_percentage ?? 0);
        });

        return round($total / $children->count(), 2);
    }
}
