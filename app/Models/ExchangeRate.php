<?php

namespace App\Models;

use App\Enums\Currency;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ExchangeRate extends Model
{
    use Multitenantable, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'organization_id',
        'base_currency',
        'target_currency',
        'rate',
        'effective_date',
        'creator_user_id',
    ];

    protected $casts = [
        'base_currency' => Currency::class,
        'target_currency' => Currency::class,
        'rate' => 'decimal:6',
        'effective_date' => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::orderedUuid()->toString();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public static function convert(float $amount, Currency $from, Currency $to, ?Carbon $date = null, ?string $orgId = null): float
    {
        if ($from === $to) return $amount;

        $date = $date ?? now();
        $orgId = $orgId ?? auth()->user()?->organization_id;

        $rate = static::where('base_currency', $from->value)
            ->where('target_currency', $to->value)
            ->where('effective_date', '<=', $date)
            ->when($orgId, fn($q) => $q->where('organization_id', $orgId))
            ->orderByDesc('effective_date')
            ->value('rate');

        if ($rate) {
            return round($amount * (float) $rate, $to->decimals());
        }

        // Try reverse
        $reverseRate = static::where('base_currency', $to->value)
            ->where('target_currency', $from->value)
            ->where('effective_date', '<=', $date)
            ->when($orgId, fn($q) => $q->where('organization_id', $orgId))
            ->orderByDesc('effective_date')
            ->value('rate');

        if ($reverseRate && (float) $reverseRate > 0) {
            return round($amount / (float) $reverseRate, $to->decimals());
        }

        return $amount;
    }
}
