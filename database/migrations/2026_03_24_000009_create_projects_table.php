<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete(); // Seul ancrage tenant
            $table->foreignUuid('project_type_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('creator_user_id')->constrained('users')->restrictOnDelete();
            $table->string('project_code');
            $table->string('title');
            $table->string('short_title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('problem_analysis')->nullable();
            $table->longText('strategy')->nullable();
            $table->longText('justification')->nullable();
            $table->longText('context_description')->nullable();
            $table->longText('ai_analysis_result')->nullable();
            $table->json('general_objectives')->nullable();
            $table->string('status')->default('brouillon'); // PHP Enum: ProjectStatus
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignUuid('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unique(['organization_id', 'project_code']);
            $table->index('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
