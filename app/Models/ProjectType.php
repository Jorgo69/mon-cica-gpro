<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectType extends Model
{
    use HasFactory, SoftDeletes, Multitenantable, HasUuid;

    protected $fillable = [
        'organization_id',
        'category_id',
        'creator_user_id',
        'name',
        'description',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function dynamicFields()
    {
        return $this->hasMany(DynamicProjectField::class)->orderBy('order');
    }
}
