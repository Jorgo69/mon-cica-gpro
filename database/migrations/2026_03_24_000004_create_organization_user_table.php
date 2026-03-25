<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_user', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('member'); // PHP Enum: org_admin | branch_admin | member
            $table->string('status')->default('active'); // PHP Enum: active | invited | suspended
            $table->timestamp('joined_at')->nullable();
            $table->primary(['user_id', 'organization_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_user');
    }
};
