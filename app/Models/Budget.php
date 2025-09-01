<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Budget extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'project_id', 'description', 'quantity', 'unit_cost',
        'total_cost', 'category', 'responsible_user_id',
    ];

     protected $dateFormat = 'Y-m-d H:i:s';
    

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->{$model->getKeyName()} = (string) Str::uuid());
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id', 'id');
    }
    public function quarterlyBudgets()
    {
        return $this->hasMany(QuarterlyBudget::class, 'budget_id', 'id');
    }
}