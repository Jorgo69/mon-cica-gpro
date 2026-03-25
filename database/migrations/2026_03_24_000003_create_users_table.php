<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('telephone', 50)->nullable();
            $table->string('numero_identification', 100)->unique()->nullable();
            $table->string('country', 10)->nullable()->index();
            $table->json('location')->nullable(); // ville, quartier, adresse
            $table->string('account_type')->default('org_member'); // PHP Enum: system_admin | org_member | independent
            $table->foreignUuid('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
