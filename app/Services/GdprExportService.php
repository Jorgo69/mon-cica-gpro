<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Models\Organization;
use App\Models\User;

class GdprExportService
{
    /**
     * Export personal data for any user role.
     */
    public static function exportPersonal(User $user): array
    {
        $user->loadMissing(['socialAccounts']);

        return [
            'account' => [
                'name' => $user->name,
                'email' => $user->email,
                'telephone' => $user->telephone,
                'sexe' => $user->sexe,
                'pays' => $user->pays,
                'ville' => $user->ville,
                'department' => $user->department,
                'role' => $user->role?->value,
                'organization_id' => $user->organization_id,
                'email_verified_at' => $user->email_verified_at?->toISOString(),
                'created_at' => $user->created_at?->toISOString(),
                'meta' => $user->meta,
            ],
            'projects_created' => \App\Models\Project::withoutGlobalScopes()
                ->where('creator_user_id', $user->id)
                ->select('id', 'title', 'project_code', 'status', 'created_at')
                ->get()
                ->toArray(),
            'activities_assigned' => \App\Models\Activity::withoutGlobalScopes()
                ->where('responsible_user_id', $user->id)
                ->select('id', 'description', 'status', 'progress_percentage', 'created_at')
                ->get()
                ->toArray(),
            'comments' => \App\Models\Comment::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->select('id', 'body', 'commentable_type', 'commentable_id', 'created_at')
                ->get()
                ->toArray(),
            'social_accounts' => $user->socialAccounts->map(fn ($sa) => [
                'provider' => $sa->provider,
                'linked_at' => $sa->created_at?->toISOString(),
            ])->toArray(),
            'notifications' => $user->notifications()
                ->select('id', 'type', 'data', 'read_at', 'created_at')
                ->limit(100)
                ->get()
                ->toArray(),
            'exported_at' => now()->toISOString(),
        ];
    }

    /**
     * Export full organization data (for ORG_ADMIN).
     */
    public static function exportOrganization(Organization $org): array
    {
        return [
            'organization' => [
                'name' => $org->name,
                'slug' => $org->slug,
                'status' => $org->status?->value,
                'plan' => $org->currentPlan()->slug,
                'description' => $org->description,
                'website' => $org->website,
                'contact_email' => $org->contact_email,
                'contact_phone' => $org->contact_phone,
                'created_at' => $org->created_at?->toISOString(),
            ],
            'members' => $org->users()
                ->select('id', 'name', 'email', 'role', 'created_at')
                ->get()
                ->toArray(),
            'projects' => $org->projects()
                ->withoutGlobalScopes()
                ->with(['logicalFramework.specificObjectives.results.activities'])
                ->get()
                ->map(fn ($p) => [
                    'title' => $p->title,
                    'project_code' => $p->project_code,
                    'status' => $p->status?->value,
                    'start_date' => $p->start_date?->toDateString(),
                    'end_date' => $p->end_date?->toDateString(),
                    'progress' => $p->calculateProjectProgress(),
                    'created_at' => $p->created_at?->toISOString(),
                    'objectives' => $p->logicalFramework
                        ? $p->logicalFramework->specificObjectives->map(fn ($so) => [
                            'description' => $so->description,
                            'results' => $so->results->map(fn ($r) => [
                                'description' => $r->description,
                                'activities' => $r->activities->map(fn ($a) => [
                                    'description' => $a->description,
                                    'status' => $a->status?->value,
                                    'progress' => $a->progress_percentage,
                                ])->toArray(),
                            ])->toArray(),
                        ])->toArray()
                        : [],
                ])
                ->toArray(),
            'budgets' => \App\Models\Budget::withoutGlobalScopes()
                ->whereHas('project', fn ($q) => $q->where('organization_id', $org->id))
                ->select('id', 'project_id', 'description', 'total_cost', 'created_at')
                ->get()
                ->toArray(),
            'expenses' => \App\Models\Expense::withoutGlobalScopes()
                ->whereHas('project', fn ($q) => $q->where('organization_id', $org->id))
                ->select('id', 'project_id', 'description', 'amount', 'expense_date', 'created_at')
                ->get()
                ->toArray(),
            'invitations' => \App\Models\Invitation::withoutGlobalScopes()
                ->where('organization_id', $org->id)
                ->select('email', 'role', 'status', 'created_at', 'expires_at')
                ->get()
                ->toArray(),
            'exported_at' => now()->toISOString(),
        ];
    }

    /**
     * Legacy method — kept for backwards compatibility.
     */
    public static function export(User $user): array
    {
        return self::exportPersonal($user);
    }
}
