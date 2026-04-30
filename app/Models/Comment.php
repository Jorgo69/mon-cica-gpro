<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Comment extends Model
{
    use SoftDeletes, Multitenantable, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'organization_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'body',
        'mentions',
    ];

    protected $casts = [
        'mentions' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::orderedUuid()->toString();
            }
            if (empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
            if (empty($model->organization_id)) {
                $model->organization_id = auth()->user()?->organization_id;
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at');
    }

    /**
     * Extract @mentions from body text.
     * Format: @[User Name](user-uuid)
     */
    public static function extractMentions(string $body): array
    {
        preg_match_all('/@\[([^\]]+)\]\(([a-f0-9-]+)\)/', $body, $matches, PREG_SET_ORDER);

        return array_map(fn($m) => ['name' => $m[1], 'user_id' => $m[2]], $matches);
    }
}
