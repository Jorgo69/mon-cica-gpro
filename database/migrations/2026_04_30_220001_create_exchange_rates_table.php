<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('base_currency', 3);
            $table->string('target_currency', 3);
            $table->decimal('rate', 14, 6);
            $table->date('effective_date');
            $table->foreignUuid('creator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['organization_id', 'base_currency', 'target_currency', 'effective_date'], 'exchange_rates_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
