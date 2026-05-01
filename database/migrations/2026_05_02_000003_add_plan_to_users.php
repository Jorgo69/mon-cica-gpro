<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan')->default('free')->after('role');
            $table->timestamp('plan_activated_at')->nullable()->after('plan');
            $table->timestamp('plan_expires_at')->nullable()->after('plan_activated_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan', 'plan_activated_at', 'plan_expires_at']);
        });
    }
};
