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
            $table->string('pays', 100)->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('role')->nullable();
            $table->foreignUuid('organization_id')
                  ->nullable()
                  ->constrained('organizations')
                  ->onDelete('cascade');
            $table->string('department')->nullable();
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
