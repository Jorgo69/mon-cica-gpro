<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sub_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('activity_id');
            $table->longText('description');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->uuid('responsible_user_id')->nullable();
            $table->string('status', 50);
            $table->text('justification')->nullable();
            $table->boolean('is_milestone')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('activity_id');
            $table->index('responsible_user_id');

            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('responsible_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_activities');
    }
};
