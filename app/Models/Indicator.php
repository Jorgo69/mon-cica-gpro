<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Indicator extends Model
{
    use HasFactory, SoftDeletes, Multitenantable, LogsActivity;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'organization_id',
        'indicatorable_type',
        'indicatorable_id',
        'description',
        'verification_source',
        'assumption',
        'baseline_value',
        'target_value',
        'current_value',
        'unit',
        'creator_user_id',
        'order',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::orderedUuid();
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function indicatorable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function measurements()
    {
        return $this->hasMany(IndicatorMeasurement::class)->orderByDesc('measured_at');
    }

    public function latestMeasurement()
    {
        return $this->hasOne(IndicatorMeasurement::class)->latestOfMany('measured_at');
    }

    public function progressPercent(): float
    {
        $baseline = (float) ($this->baseline_value ?? 0);
        $target = (float) ($this->target_value ?? 0);
        $current = (float) ($this->current_value ?? $baseline);

        if ($target === $baseline) {
            return 0;
        }

        return round(min(100, max(0, (($current - $baseline) / ($target - $baseline)) * 100)), 1);
    }

    public function trend(): string
    {
        $measurements = $this->measurements()->take(3)->get();

        if ($measurements->count() < 2) {
            return 'stable';
        }

        $latest = (float) $measurements->first()->value;
        $previous = (float) $measurements->skip(1)->first()->value;

        if ($latest > $previous) return 'up';
        if ($latest < $previous) return 'down';
        return 'stable';
    }
}
