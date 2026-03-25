<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory, SoftDeletes, Multitenantable, HasUuid;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
