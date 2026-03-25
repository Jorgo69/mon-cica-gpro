<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecificObjective extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'logical_framework_id',
        'creator_user_id',
        'description',
        'indicators',
        'verification_sources',
        'assumptions',
        'order',
    ];

    public function logicalFramework()
    {
        return $this->belongsTo(LogicalFramework::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function results()
    {
        return $this->hasMany(Result::class)->orderBy('order');
    }
}
