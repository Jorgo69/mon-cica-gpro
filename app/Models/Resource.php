<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="Resource",
 *     title="Resource",
 *     description="Resource model",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="name", type="string", example="Ciment"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="quantity", type="number"),
 *     @OA\Property(property="unit_cost", type="number", format="float"),
 *     @OA\Property(property="total_cost", type="number", format="float")
 * )
 */
class Resource extends Model
{
    use HasFactory, \App\Traits\Multitenantable, \Spatie\Activitylog\Traits\LogsActivity;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id', 'organization_id', 'activity_id', 'creator_user_id', 'name', 'type', 'quantity',
        'unit_cost', 'total_cost', 'category', 'responsible_user_id',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
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
    
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id', 'id');
    }
}