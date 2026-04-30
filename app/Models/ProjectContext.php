<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectContext extends Model
{
    use HasFactory, \App\Traits\Multitenantable;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'organization_id', 'project_id', 'creator_user_id', 'context_description', 'ai_analysis_result',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }
}