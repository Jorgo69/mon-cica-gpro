<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'organizations',
        'projects',
        'logical_frameworks',
        'specific_objectives',
        'results',
        'activities',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'meta')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->json('meta')->default('{}')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'meta')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('meta');
                });
            }
        }
    }
};
