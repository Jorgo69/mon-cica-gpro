<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailSuppression extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['email', 'reason', 'details'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::orderedUuid();
        });
    }

    /**
     * Verifie si un email est dans la suppression list.
     */
    public static function isSuppressed(string $email): bool
    {
        return static::where('email', strtolower($email))->exists();
    }

    /**
     * Ajoute un email a la suppression list.
     */
    public static function suppress(string $email, string $reason = 'bounced', ?string $details = null): static
    {
        return static::updateOrCreate(
            ['email' => strtolower($email)],
            ['reason' => $reason, 'details' => $details]
        );
    }

    /**
     * Retire un email de la suppression list.
     */
    public static function unsuppress(string $email): bool
    {
        return (bool) static::where('email', strtolower($email))->delete();
    }
}
