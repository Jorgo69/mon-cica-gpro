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
            $table->foreignUuid('logical_framework_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('creator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('description');
            $table->longText('indicators')->nullable();
            $table->longText('verification_sources')->nullable();
            $table->longText('assumptions')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specific_objectives');
    }
};
