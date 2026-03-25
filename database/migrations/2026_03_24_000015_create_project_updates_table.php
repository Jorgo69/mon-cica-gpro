<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fusion de progress_trackers + qualitative_evaluations.
        // Le champ `type` distingue les mises à jour opérationnelles des évaluations formelles.
        Schema::create('project_updates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('activity_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('creator_user_id')->constrained('users')->restrictOnDelete();
            $table->string('type'); // PHP Enum: progress | evaluation
            $table->date('date');
            $table->unsignedTinyInteger('progress_percentage')->nullable();
            $table->text('status_update')->nullable();
            $table->text('justification')->nullable();
            $table->string('rating', 100)->nullable();
            $table->unsignedSmallInteger('score')->nullable();
            $table->text('comment')->nullable();
            $table->json('meta')->nullable(); // Extensible : IA, pièces jointes, etc.
            $table->index(['activity_id', 'date']);
            $table->index(['project_id', 'date']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_updates');
    }
};
