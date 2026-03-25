<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Enums\UpdateType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectUpdate extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'activity_id',
        'project_id',
        'creator_user_id',
        'type',
        'date',
        'progress_percentage',
        'status_update',
        'justification',
        'rating',
        'score',
        'comment',
        'meta',
    ];

    protected $casts = [
        'type' => UpdateType::class,
        'date' => 'date',
        'meta' => 'array',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }
}
