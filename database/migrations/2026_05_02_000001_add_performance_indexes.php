<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfNotExists('activities', 'responsible_user_id');
        $this->addIndexIfNotExists('projects', 'is_template');

        // Composite indexes
        $this->addCompositeIfNotExists('activities', ['organization_id', 'status'], 'activities_org_status_index');
        $this->addCompositeIfNotExists('activities', ['result_id', 'status'], 'activities_result_status_index');
        $this->addCompositeIfNotExists('projects', ['organization_id', 'status'], 'projects_org_status_index');
        $this->addCompositeIfNotExists('comments', ['commentable_type', 'commentable_id'], 'comments_commentable_index');
        $this->addCompositeIfNotExists('attachments', ['attachable_type', 'attachable_id'], 'attachments_attachable_index');
        $this->addCompositeIfNotExists('indicators', ['indicatorable_type', 'indicatorable_id'], 'indicators_indicatorable_index');

        $this->addIndexIfNotExists('indicator_measurements', 'organization_id');
        $this->addIndexIfNotExists('share_tokens', 'project_id');
        $this->addIndexIfNotExists('expenses', 'project_id');
        $this->addIndexIfNotExists('expenses', 'budget_id');
        $this->addIndexIfNotExists('budgets', 'project_id');

        // FK owner_user_id sur organizations (apres creation de la table users)
        try {
            Schema::table('organizations', function (Blueprint $table) {
                $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // FK already exists, skip
        }
    }

    public function down(): void
    {
        // Indexes are safe to leave
    }

    private function addIndexIfNotExists(string $table, string $column): void
    {
        try {
            Schema::table($table, fn (Blueprint $t) => $t->index($column));
        } catch (\Exception $e) {
            // Index already exists, skip
        }
    }

    private function addCompositeIfNotExists(string $table, array $columns, string $name): void
    {
        try {
            Schema::table($table, fn (Blueprint $t) => $t->index($columns, $name));
        } catch (\Exception $e) {
            // Index already exists, skip
        }
    }
};
