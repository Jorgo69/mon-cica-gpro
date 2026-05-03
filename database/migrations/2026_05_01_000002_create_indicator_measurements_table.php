<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicator_measurements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('indicator_id');
            $table->uuid('organization_id')->nullable();
            $table->uuid('measured_by_user_id');
            $table->string('value');
            $table->text('comment')->nullable();
            $table->date('measured_at');
            $table->timestamps();

            $table->foreign('indicator_id')->references('id')->on('indicators')->onDelete('cascade');
            $table->foreign('measured_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['indicator_id', 'measured_at']);
        });

        // Add current_value to indicators
        Schema::table('indicators', function (Blueprint $table) {
            $table->string('current_value')->nullable()->after('target_value');
            $table->string('unit')->nullable()->after('current_value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicator_measurements');
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn(['current_value', 'unit']);
        });
    }
};
