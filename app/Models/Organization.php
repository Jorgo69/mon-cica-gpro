<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Enums\OrganizationStatus;
use App\Enums\OrganizationType;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'type',
        'country',
        'location',
        'contact',
        'status',
    ];

    protected $casts = [
        'status'   => OrganizationStatus::class,
        'type'     => OrganizationType::class,
        'location' => 'array',
        'contact'  => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // Relations

    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function branches()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'status', 'joined_at')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function adminAccessGrants()
    {
        return $this->hasMany(AdminAccessGrant::class);
    }
}
