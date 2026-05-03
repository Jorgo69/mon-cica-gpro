<?php

namespace App\Services;

use App\Models\User;

class GdprExportService
{
    public static function export(User $user): array
    {
        $user->loadMissing([
            'socialAccounts',
        ]);

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
}
