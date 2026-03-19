<?php

namespace App\Queries;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityQueries
{
    /**
     * Get paginated activities for a specific user with filters.
     *
     * @param User $user
     * @param array $filters (search, statusFilter, responsibleUserFilter)
     * @param string $sortField
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findActivityWithDetails(string $id): Activity
    {
        return Activity::with([
            'responsibleUser',
            'result.specificObjective.logicalFramework.project.creator',
            'resources.responsibleUser',
            'children.responsibleUser'
        ])->findOrFail($id);
    }


    public function getPaginatedActivitiesForUser(
        User $user,
        array $filters = [],
        string $sortField = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Activity::with('responsibleUser', 'result.specificObjective.logicalFramework.project')
            ->orderBy($sortField, $sortDirection);

        // Security / Scoping: Limit to the current user's activities (as an example of responsible user)
        // Adapt according to exact Policy/Requirements if IT_ADMIN can see everything.
        // Based on original logic:
        // $activities->where('responsible_user_id', $user->id);
        // We ensure this constraint is preserved unless the user is an admin.
        if (!$user->hasRole('IT_ADMIN')) {
            $query->where('responsible_user_id', $user->id);
        }

        // Apply Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('result.specificObjective.logicalFramework.project', function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('short_title', 'like', '%' . $search . '%')
                  ->orWhere('project_code', 'like', '%' . $search . '%');
            });
        }

        // Apply Status Filter
        if (!empty($filters['statusFilter'])) {
            $query->where('status', $filters['statusFilter']);
        }

        // Apply Responsible Filter
        if (!empty($filters['responsibleUserFilter'])) {
            $query->where('responsible_user_id', $filters['responsibleUserFilter']);
        }

        return $query->paginate($perPage);
    }
}
