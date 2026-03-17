<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProjectType extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\Multitenantable;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'organization_id', 'creator_user_id', 'name', 'description', 'category'];
    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::uuid());
    }
    public function projects()
    {
        return $this->hasMany(Project::class, 'project_type_id', 'id');
    }
    public function dynamicFields()
    {
        return $this->hasMany(DynamicProjectField::class, 'project_type_id', 'id')->orderBy('order');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id', 'id');
    }
}