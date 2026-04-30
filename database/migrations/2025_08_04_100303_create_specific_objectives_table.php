<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specific_objectives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable();
            $table->uuid('logical_framework_id');
            $table->uuid('creator_user_id')->nullable();
            $table->longText('description');
            $table->longText('indicators')->nullable();
            $table->longText('verification_sources')->nullable();
            $table->longText('assumptions')->nullable();
            $table->json('meta')->default('{}')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('logical_framework_id');
            $table->index('creator_user_id');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('logical_framework_id')->references('id')->on('logical_frameworks')->onDelete('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specific_objectives');
    }
};
