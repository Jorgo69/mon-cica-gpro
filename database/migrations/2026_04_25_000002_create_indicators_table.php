<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable();

            // Relation polymorphe : LogicalFramework, SpecificObjective, ou Result
            $table->string('indicatorable_type');
            $table->uuid('indicatorable_id');

            $table->text('description');
            $table->text('verification_source')->nullable();
            $table->text('assumption')->nullable();
            $table->text('baseline_value')->nullable();
            $table->text('target_value')->nullable();

            $table->uuid('creator_user_id')->nullable();
            $table->unsignedSmallInteger('order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['indicatorable_type', 'indicatorable_id'], 'indicators_morph_index');
            $table->index('organization_id');
            $table->index('creator_user_id');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
