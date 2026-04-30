<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_types', function (Blueprint $table) {
            $table->boolean('is_system')->default(false)->after('category');
            $table->boolean('is_active')->default(true)->after('is_system');
        });

        Schema::table('general_administrations', function (Blueprint $table) {
            $table->boolean('is_system')->default(false)->after('type');
            $table->boolean('is_active')->default(true)->after('is_system');
        });
    }

    public function down(): void
    {
        Schema::table('project_types', function (Blueprint $table) {
            $table->dropColumn(['is_system', 'is_active']);
        });

        Schema::table('general_administrations', function (Blueprint $table) {
            $table->dropColumn(['is_system', 'is_active']);
        });
    }
};
