<?php
namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id', 'creator_user_id', 'project_type_id', 'project_code', 'title', 'status',
        'short_title', 'description', 'general_objectives', 'start_date', 'end_date', 'status',
        'problem_analysis', 'strategy', 'justification', 'created_by_user_id', 'updated_by_user_id'
    ];
    protected $casts = [
        'start_date' => 'date', 'end_date' => 'date',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::uuid());
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
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
        return $this->hasMany(ProjectDocument::class, 'project_id', 'id');
    }
    public function logicalFramework()
    {
        return $this->hasOne(LogicalFramework::class, 'project_id', 'id');
    }
    public function budgets()
    {
        return $this->hasMany(Budget::class, 'project_id', 'id');
    }
    public function progressTrackers()
    {
        return $this->hasMany(ProgressTracker::class, 'project_id', 'id');
    }
    public function qualitativeEvaluations()
    {
        return $this->hasMany(QualitativeEvaluation::class, 'project_id', 'id');
    }

    // Autres relations et méthodes...
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
    // public function calculateProgress(): float
    // {
    //     // Use the new method to get all activities
    //     $activities = $this->getAllActivities();

    //     // If there are no activities, the progress is 0.
    //     if ($activities->isEmpty()) {
    //         return 0.0;
    //     }

    //     // Sum up the progress percentages of all activities.
    //     $totalProgress = $activities->sum('progress_percentage');
        
    //     // Calculate the average progress.
    //     // Divide by the count of activities.
    //     $averageProgress = $totalProgress / $activities->count();
        
    //     // Return the average progress, rounded to two decimal places.
    //     return round($averageProgress, 2);
    // }

    // App\Models\Project.php

    // public function calculateProjectProgress(): float
    // {
    //     $activities = $this->getAllActivities(); // Collection d'activités

    //     if ($activities->isEmpty()) {
    //         return 0.0;
    //     }

    //     // On ne prend en compte que les activités démarrées ou terminées
    //     $validActivities = $activities->whereIn('status', ['En Cours', 'Terminé']);

    //     if ($validActivities->isEmpty()) {
    //         return 0.0;
    //     }

    //     $completed = $validActivities->where('status', 'Terminé')->count();
    //     $total = $validActivities->count();

    //     return round(($completed / $total) * 100, 2);
    // }

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