<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('project_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('budget_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('activity_id')->nullable();
            $table->foreignUuid('creator_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('description');
            $table->decimal('amount', 14, 2);
            $table->date('expense_date');
            $table->string('category')->nullable();
            $table->string('reference')->nullable(); // numero facture, bon, etc.
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'expense_date']);
            $table->index('budget_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
