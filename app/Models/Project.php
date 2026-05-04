<?php
namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\Multitenantable, \Spatie\Activitylog\Traits\LogsActivity, \App\Traits\HasMeta;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'organization_id',
        'creator_user_id',
        'project_type_id',
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
        'currency',
        'start_date',
        'end_date',
        'is_template',
        'source_project_id',
        'meta',
    ];

    protected $casts = [
        'status' => \App\Enums\ProjectStatus::class,
        'currency' => \App\Enums\Currency::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'general_objectives' => 'array',
        'is_template' => 'boolean',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::orderedUuid());

        // Auto-assign creator as project member
        static::created(function ($project) {
            if ($project->creator_user_id) {
                $project->addMember(User::find($project->creator_user_id), 'creator');
            }
        });
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(\Spatie\Activitylog\Models\Activity $activity, string $eventName)
    {
        $activity->organization_id = $this->organization_id ?? auth()->user()?->organization_id;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function addMember(User $user, string $role = 'member'): void
    {
        if (!$this->members()->where('user_id', $user->id)->exists()) {
            $this->members()->attach($user->id, ['role' => $role]);
        }
    }

    public function removeMember(User $user): void
    {
        $this->members()->detach($user->id);
    }

    public function scopeVisibleTo($query, User $user)
    {
        // ORG_ADMIN and ROOT see all projects in their org
        if (in_array($user->role, [\App\Enums\AccountType::ORG_ADMIN, \App\Enums\AccountType::ROOT])) {
            return $query;
        }

        // Others see only projects they are assigned to or created
        return $query->where(function ($q) use ($user) {
            $q->where('creator_user_id', $user->id)
              ->orWhereHas('members', fn ($m) => $m->where('user_id', $user->id));
        });
    }
    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'project_type_id', 'id');
    }
    public function contexts()
    {
        return $this->hasMany(ProjectContext::class, 'project_id', 'id');
    }
    public function documents()
    {
        return $this->projectDocuments();
    }
    public function logicalFramework()
    {
        return $this->hasOne(LogicalFramework::class, 'project_id', 'id');
    }
    public function budgets()
    {
        return $this->hasMany(Budget::class, 'project_id', 'id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function progressTrackers()
    {
        return $this->hasMany(ProgressTracker::class, 'project_id', 'id');
    }
    public function qualitativeEvaluations()
    {
        return $this->hasMany(QualitativeEvaluation::class, 'project_id', 'id');
    }

    public function shareTokens()
    {
        return $this->hasMany(ShareToken::class);
    }

    public function projectContext()
    {
        return $this->hasOne(ProjectContext::class);
    }

    public function projectDocuments()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function getAllActivities(): Collection
    {
        $activities = collect([]);
        // Eager load logical framework and its descendants to minimize queries
        $this->loadMissing('logicalFramework.specificObjectives.results.activities');

        if ($this->logicalFramework) {
            foreach ($this->logicalFramework->specificObjectives as $specificObjective) {
                foreach ($specificObjective->results as $result) {
                    $activities = $activities->merge($result->activities);
                }
            }
        }

        return $activities;
    }


    /**
     * Calculate the overall progress percentage of the project.
     * This is based on the average progress of all its activities.
     *
     * @return float
     */
    

    public function calculateProjectProgress(): float
    {
        $activities = $this->getAllActivities();

        if ($activities->isEmpty()) {
            return 0.0;
        }

        $totalProgress = $activities->sum->calculateProgress(); // magie de Laravel : sum sur méthode
        // ou : $activities->sum(fn($a) => $a->calculateProgress());

        return round($totalProgress / $activities->count(), 2);
    }
    
}