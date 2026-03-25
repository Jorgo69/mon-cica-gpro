<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Result extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'specific_objective_id',
        'creator_user_id',
        'description',
        'indicators',
        'verification_sources',
        'order',
    ];

    public function specificObjective()
    {
        return $this->belongsTo(SpecificObjective::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->orderBy('order');
    }
}
