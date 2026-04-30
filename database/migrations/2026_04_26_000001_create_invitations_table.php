<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->string('code', 8)->index();
            $table->foreignUuid('organization_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('invited_by')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('org_user');
            $table->string('spatie_role')->default('MEMBER');
            $table->string('status')->default('pending');
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['email', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
