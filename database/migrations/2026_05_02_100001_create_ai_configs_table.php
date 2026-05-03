<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('configurable_type'); // App\Models\Organization or App\Models\User
            $table->uuid('configurable_id');
            $table->string('provider'); // AiProvider enum value
            $table->text('api_key_encrypted')->nullable(); // encrypt()/decrypt()
            $table->string('base_url')->nullable(); // for custom provider
            $table->string('model')->nullable(); // model name override
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->unique(['configurable_type', 'configurable_id']);
            $table->index(['configurable_type', 'configurable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_configs');
    }
};
