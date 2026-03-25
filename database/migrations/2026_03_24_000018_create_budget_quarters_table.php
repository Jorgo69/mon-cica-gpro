<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_quarters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('budget_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('quarter'); // 1 | 2 | 3 | 4
            $table->decimal('amount', 15, 2);
            $table->unique(['budget_id', 'year', 'quarter']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_quarters');
    }
};
