<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'project_id',
        'creator_user_id',
        'description',
        'category',
        'quantity',
        'unit_cost',
        'total_cost',
    ];

    protected $casts = [
        'unit_cost'  => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function quarters()
    {
        return $this->hasMany(BudgetQuarter::class);
    }
}
