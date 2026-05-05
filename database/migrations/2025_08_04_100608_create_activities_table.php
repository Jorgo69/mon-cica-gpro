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
            $table->uuid('organization_id')->nullable();
            $table->uuid('result_id')->nullable();
            $table->uuid('parent_id')->nullable();
            $table->uuid('creator_user_id')->nullable();
            $table->longText('description');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->uuid('responsible_user_id')->nullable();
            $table->string('status', 50)->nullable();
            $table->integer('budget')->nullable();
            $table->text('justification')->nullable();
            $table->boolean('is_milestone')->default(false);
            $table->integer('progress_percentage')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('result_id');
            $table->index('parent_id');
            $table->index('creator_user_id');
            $table->index('responsible_user_id');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('result_id')->references('id')->on('results')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('responsible_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
