<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="Activity",
 *     title="Activity",
 *     description="Activity model",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed", "overdue"}),
 *     @OA\Property(property="progress", type="number", format="float", example=45.5)
 * )
 */
class Activity extends Model
{
    use HasFactory, \App\Traits\Multitenantable, \Spatie\Activitylog\Traits\LogsActivity, \App\Traits\HasMeta;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'organization_id', 'result_id', 'parent_id', 'creator_user_id',
        'description', 'start_date', 'end_date', 'budget', 'responsible_user_id', 'status',
        'justification', 'is_milestone', 'progress_percentage', 'meta',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = [
        'status' => \App\Enums\ActivityStatus::class,
        'end_date' => 'date',
        'is_milestone' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::uuid());
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
    
    public function result()
    {
        return $this->belongsTo(Result::class, 'result_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Activity::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id', 'id');
    }
    public function resources()
    {
        return $this->hasMany(Resource::class, 'activity_id', 'id');
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
        // On évite les appels récursifs via ->project sur le parent
        // On remonte manuellement pour plus de sécurité
        $current = $this;
        $depth = 0;
        $maxDepth = 10; // Sécurité anti-boucle

        while ($current->parent_id && $depth < $maxDepth) {
            $current = $current->parent;
            $depth++;
        }
        
        // Une fois au sommet de la hiérarchie des activités, on récupère le projet via le résultat
        return $current->result?->specificObjective?->logicalFramework?->project ?? null;
    }

    public function getPlannedProgressPercentage(): float
    {
        if (! $this->start_date || ! $this->end_date) {
            return 0.0;
        }

        $startDate = \Carbon\Carbon::parse($this->start_date);
        $endDate = \Carbon\Carbon::parse($this->end_date);
        $today = now();

        if ($today->lt($startDate)) {
            return 0.0;
        }

        if ($today->gte($endDate)) {
            return 100.0;
        }

        $totalDuration = $startDate->diffInDays($endDate);
        $elapsed = $startDate->diffInDays($today);

        if ($totalDuration === 0) {
            return 100.0;
        }

        return round(($elapsed / $totalDuration) * 100, 2);
    }

    public function calculateProgress(): float
    {
        // Utilisation de eager loading 'children' dans le contexte d'appel est conseillé
        $children = $this->children;

        if ($children->isEmpty()) {
            return $this->status === \App\Enums\ActivityStatus::COMPLETED ? 100.0 : (float) ($this->progress_percentage ?? 0);
        }

        $statusWeight = [
            \App\Enums\ActivityStatus::DRAFT->value      => 0,
            \App\Enums\ActivityStatus::ABANDONED->value  => 0,
            \App\Enums\ActivityStatus::STOPPED->value    => 0,
            \App\Enums\ActivityStatus::PENDING->value    => 0,
            \App\Enums\ActivityStatus::ONGOING->value    => 50,
            \App\Enums\ActivityStatus::SUSPENDED->value  => 25,
            \App\Enums\ActivityStatus::COMPLETED->value  => 100,
            \App\Enums\ActivityStatus::OVERDUE->value    => 10,
        ];

        // On évite calculateProgress sur les enfants si on est déjà trop profond ou si on veut rester simple
        // Ici on fait une somme pondérée simple
        $totalProgress = $children->sum(function ($child) use ($statusWeight) {
            // Si l'enfant a lui-même des enfants, on pourrait appeler récursivement, 
            // mais attention aux performances. Pour l'instant on reste sur le statut de l'enfant direct
            // pour éviter le timeout constaté.
            
            $statusValue = $child->status instanceof \App\Enums\ActivityStatus 
                ? $child->status->value 
                : $child->status;
                
            return $statusWeight[$statusValue] ?? (float) ($child->progress_percentage ?? 0);
        });

        return round($totalProgress / $children->count(), 2);
    }
}