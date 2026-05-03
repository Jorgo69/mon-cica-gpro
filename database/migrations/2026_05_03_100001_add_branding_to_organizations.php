<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('name');
            $table->string('website')->nullable()->after('logo_path');
            $table->string('contact_email')->nullable()->after('website');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->text('description')->nullable()->after('contact_phone');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'website', 'contact_email', 'contact_phone', 'description']);
        });
    }
};
