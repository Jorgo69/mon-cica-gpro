<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes, Multitenantable, HasUuid;

    protected $fillable = [
        'organization_id',
        'type',
        'name',
        'description',
        'meta',
        'order',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function projectTypes()
    {
        return $this->hasMany(ProjectType::class);
    }
}
