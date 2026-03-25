<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('result_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('project_id')->constrained()->cascadeOnDelete(); // Dénorm pour queries directes
            $table->foreignUuid('parent_id')->nullable()->constrained('activities')->cascadeOnDelete(); // Sous-activités
            $table->foreignUuid('creator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('description');
            $table->text('justification')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->nullable(); // PHP Enum: ActivityStatus
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->boolean('is_milestone')->default(false);
            $table->unsignedSmallInteger('order')->default(0);
            $table->index('project_id');
            $table->index('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
