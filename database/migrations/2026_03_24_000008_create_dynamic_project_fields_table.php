<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dynamic_project_fields', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_type_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('organization_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('creator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('field_name');
            $table->text('question_text');
            $table->string('input_type'); // PHP Enum: text | textarea | select | date | number
            $table->json('options')->nullable();
            $table->string('section')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->string('target_project_field')->nullable();
            $table->string('delimiter_start')->nullable();
            $table->string('delimiter_end')->nullable();
            $table->string('render_as')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unique(['organization_id', 'project_type_id', 'field_name'], 'dpf_org_type_field_unique');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_project_fields');
    }
};
