<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BudgetQuarter extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'budget_id',
        'year',
        'quarter',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
