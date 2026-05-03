<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IndicatorMeasurement extends Model
{
    use Multitenantable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'indicator_id',
        'organization_id',
        'measured_by_user_id',
        'value',
        'comment',
        'measured_at',
    ];

    protected $casts = [
        'measured_at' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::orderedUuid());
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function measuredBy()
    {
        return $this->belongsTo(User::class, 'measured_by_user_id');
    }
}
