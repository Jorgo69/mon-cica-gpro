<?php

namespace App\Models;

use App\Enums\AiProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AiConfig extends Model
{
    use LogsActivity;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'configurable_type',
        'configurable_id',
        'provider',
        'api_key_encrypted',
        'base_url',
        'model',
        'enabled',
    ];

    protected $casts = [
        'provider' => AiProvider::class,
        'enabled' => 'boolean',
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

    // ─── Relations ───────────────────────────────────────────

    public function configurable()
    {
        return $this->morphTo();
    }

    // ─── Accessors ───────────────────────────────────────────

    public function getApiKeyAttribute(): ?string
    {
        if (empty($this->api_key_encrypted)) {
            return null;
        }

        try {
            return decrypt($this->api_key_encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setApiKeyAttribute(?string $value): void
    {
        $this->attributes['api_key_encrypted'] = $value ? encrypt($value) : null;
    }

    // ─── Helpers ─────────────────────────────────────────────

    public function getEffectiveBaseUrl(): string
    {
        return $this->base_url ?: $this->provider->baseUrl();
    }

    public function getEffectiveModel(): string
    {
        return $this->model ?: $this->provider->defaultModel();
    }

    public function isUsable(): bool
    {
        return $this->enabled && !empty($this->api_key);
    }

    /**
     * Masked API key for display (show first 8 + last 4 chars).
     */
    public function getMaskedKeyAttribute(): ?string
    {
        $key = $this->api_key;
        if (!$key || strlen($key) < 16) {
            return $key ? '****' : null;
        }

        return substr($key, 0, 8) . '...' . substr($key, -4);
    }
}
