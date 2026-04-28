<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QualitativeEvaluation extends Model
{
    use HasFactory, \App\Traits\Multitenantable;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'organization_id', 'project_id', 'activity_id', 'creator_user_id', 'rating', 'score', 'comments',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::orderedUuid());
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }
}