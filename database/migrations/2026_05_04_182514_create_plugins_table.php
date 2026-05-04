<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('version')->default('1.0.0');
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->string('provider_class');
            $table->string('path');
            $table->json('hooks')->nullable();
            $table->json('permissions')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('installed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('organization_plugin', function (Blueprint $table) {
            $table->uuid('organization_id');
            $table->uuid('plugin_id');
            $table->boolean('is_enabled')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->primary(['organization_id', 'plugin_id']);
            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('plugin_id')->references('id')->on('plugins')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_plugin');
        Schema::dropIfExists('plugins');
    }
};
