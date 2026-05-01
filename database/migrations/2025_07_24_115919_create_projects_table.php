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
            $table->uuid('organization_id')->nullable();
            $table->uuid('creator_user_id');
            $table->uuid('project_type_id')->nullable();
            $table->string('project_code')->unique();
            $table->string('title');
            $table->string('short_title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('context_description')->nullable();
            $table->json('general_objectives')->nullable();
            $table->longText('problem_analysis')->nullable();
            $table->longText('strategy')->nullable();
            $table->longText('justification')->nullable();
            $table->longText('ai_analysis_result')->nullable();
            $table->string('status')->default('brouillon');
            $table->string('currency', 3)->default('XOF');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_template')->default(false);
            $table->uuid('source_project_id')->nullable();
            $table->json('meta')->default('{}')->nullable();
            $table->uuid('created_by_user_id')->nullable();
            $table->uuid('updated_by_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('creator_user_id');
            $table->index('project_type_id');
            $table->index('status');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('project_type_id')->references('id')->on('project_types')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
