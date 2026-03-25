<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogicalFramework extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'project_id',
        'creator_user_id',
        'general_objective',
        'general_obj_indicators',
        'general_obj_verification_sources',
        'assumptions',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function specificObjectives()
    {
        return $this->hasMany(SpecificObjective::class)->orderBy('order');
    }
}
