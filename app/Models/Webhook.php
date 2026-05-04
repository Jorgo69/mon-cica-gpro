<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Webhook extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organization_id',
        'url',
        'secret',
        'events',
        'label',
        'is_active',
        'last_triggered_at',
        'failure_count',
    ];

    protected $casts = [
        'events' => 'array',
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = $model->id ?: (string) Str::uuid();
            if (empty($model->secret)) {
                $model->secret = Str::random(64);
            }
        });
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function listensTo(string $event): bool
    {
        return in_array($event, $this->events ?? []);
    }

    public const AVAILABLE_EVENTS = [
        'project.created',
        'project.updated',
        'project.deleted',
        'project.status_changed',
        'activity.created',
        'activity.completed',
        'activity.overdue',
        'budget.threshold_exceeded',
        'member.invited',
        'member.joined',
    ];
}
