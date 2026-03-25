<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remplace general_administrations — table de référence configurable par organisation.
        // Utilisée pour les catégories de types de projets et autres labels personnalisables.
        // Les statuts (projets, activités) sont gérés exclusivement par les PHP Enums.
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('type'); // ex: project_type_category
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('meta')->nullable(); // color, icon, données UI
            $table->unsignedSmallInteger('order')->default(0);
            $table->index(['organization_id', 'type']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
