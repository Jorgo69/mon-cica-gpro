<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('price')->default(0);
            $table->string('currency', 10)->default('FCFA');
            $table->string('billing_period', 20)->default('month');
            $table->integer('max_projects')->default(1);
            $table->integer('max_members')->default(3);
            $table->json('features')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Add plan_id to organizations (replace string plan column)
        Schema::table('organizations', function (Blueprint $table) {
            $table->uuid('plan_id')->nullable()->after('status');
        });

        // Add plan_id to users (for independents)
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('plan_id')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('plan_id');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('plan_id');
        });

        Schema::dropIfExists('plans');
    }
};
