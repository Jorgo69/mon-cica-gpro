<?php

namespace App\Models;

use App\Enums\InvitationStatus;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Invitation extends Model
{
    use SoftDeletes, LogsActivity, Multitenantable;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'token',
        'code',
        'organization_id',
        'invited_by',
        'role',
        'spatie_role',
        'status',
        'expires_at',
        'accepted_at',
    ];

    protected $casts = [
        'status' => InvitationStatus::class,
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
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
            ->logOnly(['email', 'status', 'role', 'spatie_role'])
            ->logOnlyDirty();
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', InvitationStatus::PENDING);
    }

    public function scopeValid($query)
    {
        return $query->where('status', InvitationStatus::PENDING)
                     ->where('expires_at', '>', now());
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->status === InvitationStatus::PENDING && !$this->isExpired();
    }
}
