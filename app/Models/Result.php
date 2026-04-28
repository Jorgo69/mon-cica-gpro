<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Result extends Model
{
    use HasFactory, \App\Traits\Multitenantable, \App\Traits\HasMeta;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'organization_id', 'specific_objective_id', 'creator_user_id', 'description', 'meta',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::uuid());
    }
    
    public function specificObjective()
    {
        return $this->belongsTo(SpecificObjective::class, 'specific_objective_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'result_id', 'id');
    }

    public function indicatorItems()
    {
        return $this->morphMany(Indicator::class, 'indicatorable')->orderBy('order');
    }
}