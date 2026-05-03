<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->uuid('owner_user_id')->nullable()->after('id');
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Backfill: assign the first org_admin as owner for existing orgs
        $orgs = \App\Models\Organization::whereNull('owner_user_id')->get();
        foreach ($orgs as $org) {
            $firstAdmin = \App\Models\User::where('organization_id', $org->id)
                ->where('role', 'org_admin')
                ->oldest()
                ->first();
            if ($firstAdmin) {
                $org->update(['owner_user_id' => $firstAdmin->id]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['owner_user_id']);
            $table->dropColumn('owner_user_id');
        });
    }
};
