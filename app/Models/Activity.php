<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'result_id', 'description', 'start_date', 'end_date', 'budget',
        'responsible_user_id', 'status', 'justification', 'is_milestone', 'progress_percentage',
    ];

    protected $casts = [
        // 'start_date' => 'date', 
        'end_date' => 'date',
        'is_milestone' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::uuid());
    }
    
    public function result()
    {
        return $this->belongsTo(Result::class, 'result_id', 'id');
    }
    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id', 'id');
    }
    public function resources()
    {
        return $this->hasMany(Resource::class, 'activity_id', 'id');
    }
    public function subActivities()
    {
        return $this->hasMany(SubActivity::class, 'activity_id', 'id');
    }
    public function progressTrackers()
    {
        return $this->hasMany(ProgressTracker::class, 'activity_id', 'id');
    }
    public function qualitativeEvaluations()
    {
        return $this->hasMany(QualitativeEvaluation::class, 'activity_id', 'id');
    }



    /**
     * Accesseur pour obtenir le projet parent de l'activité.
     * Permet d'appeler $activity->project.
     */
    public function getProjectAttribute()
    {
        // Retourne le projet en suivant la chaîne de relations
        return $this->result?->specificObjective?->logicalFramework?->project ?? null;
    }

    // App\Models\Activity.php

    public function getPlannedProgressPercentage(): float
    {
        if (! $this->start_date || ! $this->end_date) {
            return 0.0;
        }

        $startDate = \Carbon\Carbon::parse($this->start_date);
        $endDate = \Carbon\Carbon::parse($this->end_date);
        $today = now();

        // Si on n’a pas encore commencé
        if ($today->lt($startDate)) {
            return 0.0;
        }

        // Si on est terminé
        if ($today->gte($endDate)) {
            return 100.0;
        }

        // Progression linéaire dans le temps
        $totalDuration = $startDate->diffInDays($endDate);
        $elapsed = $startDate->diffInDays($today);

        if ($totalDuration === 0) {
            return 100.0;
        }

        return round(($elapsed / $totalDuration) * 100, 2);
    }

    // App\Models\Activity.php
    
    // public function calculateProgress(): float
    // {
    //     $total = $this->subActivities()->count();

    //     if ($total === 0) {
    //         return 0.0;
    //     }

    //     $completed = $this->subActivities()
    //         ->where('status', 'Terminé')
    //         ->count();

    //     // dd($completed);

    //     return round(($completed / $total) * 100, 2);
    // }

    public function calculateProgress(): float
    {
        $subActivities = $this->subActivities;

        if ($subActivities->isEmpty()) {
            return 0.0;
        }

        // 🔹 Définis ici le poids de chaque statut
        $statusWeight = [
            'Brouillon'  => 0,
            'En Attente' => 10,
            'En Cours'   => 50,
            'Suspendu'   => 50,
            'Terminé'    => 100,
        ];

        $totalProgress = $subActivities->sum(function ($subActivity) use ($statusWeight) {
            return $statusWeight[$subActivity->status] ?? 0; // 0 si inconnu
        });

        $average = $totalProgress / $subActivities->count();

        return round($average, 2);
    }

}