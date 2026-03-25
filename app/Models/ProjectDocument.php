<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectDocument extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'project_id',
        'creator_user_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }
}
