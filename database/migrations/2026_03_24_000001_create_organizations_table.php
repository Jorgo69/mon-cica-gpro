<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('parent_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('headquarters'); // PHP Enum: headquarters | branch
            $table->string('country', 10)->nullable()->index();
            $table->json('location')->nullable(); // city, region, quartier, adresse, code_postal
            $table->json('contact')->nullable();  // phone, email, website, fax
            $table->string('status')->default('trial'); // PHP Enum: trial | active | suspended | cancelled
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
