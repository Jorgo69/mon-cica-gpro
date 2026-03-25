<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Multitenantable;
use App\Enums\FieldType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DynamicProjectField extends Model
{
    use HasFactory, SoftDeletes, Multitenantable, HasUuid;

    protected $fillable = [
        'project_type_id',
        'organization_id',
        'creator_user_id',
        'field_name',
        'question_text',
        'input_type',
        'options',
        'section',
        'order',
        'target_project_field',
        'delimiter_start',
        'delimiter_end',
        'render_as',
        'is_required',
    ];

    protected $casts = [
        'options'     => 'array',
        'is_required' => 'boolean',
        'input_type'  => FieldType::class,
    ];

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }
}
