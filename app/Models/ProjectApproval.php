<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectApproval extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'project_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'comment',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->id = $model->id ?: (string) Str::uuid());
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
