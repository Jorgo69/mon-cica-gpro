<?php

namespace App\Services\Search;

use App\Models\Project;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Collection;

class GlobalSearchService
{
    /**
     * Perform a global search across multiple models.
     *
     * @param string $term
     * @param int $limit
     * @return Collection
     */
    public function search(string $term, int $limit = 5): Collection
    {
        if (empty($term) || strlen($term) < 2) {
            return collect();
        }

        $results = collect();

        // Le Global Scope Multitenantable filtre automatiquement par org/creator
        // ROOT voit tout, org_user voit son org, INDEPENDENT voit ses propres donnees
        $projects = Project::query()
            ->where(function ($query) use ($term) {
                $query->where('title', 'like', "%{$term}%")
                    ->orWhere('project_code', 'like', "%{$term}%")
                    ->orWhere('short_title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            })
            ->limit($limit)
            ->get()
            ->map(function ($project) {
                return [
                    'type' => 'Projet',
                    'title' => $project->title,
                    'subtitle' => $project->project_code . ' - ' . ($project->status?->label() ?? 'État inconnu'),
                    'url' => route('project.dashboard', ['projectId' => $project->id]),
                    'icon' => 'folder-kanban',
                ];
            });
        $results = $results->concat($projects);

        // Search Activities
        $activities = Activity::query()
            ->where('description', 'like', "%{$term}%")
            ->limit($limit)
            ->get()
            ->map(function ($activity) {
                return [
                    'type' => 'Activité',
                    'title' => $activity->description,
                    'subtitle' => 'Projet: ' . ($activity->project?->short_title ?? 'N/A'),
                    'url' => route('project.dashboard', ['projectId' => $activity->project?->id]), 
                    'icon' => 'activity',
                ];
            });
        $results = $results->concat($activities);

        // Search Users (Membres)
        $users = User::query()
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            })
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'Membre',
                    'title' => $user->name,
                    'subtitle' => $user->email,
                    'url' => route('admin.member.list'), 
                    'icon' => 'users',
                ];
            });
        $results = $results->concat($users);

        return $results;
    }
}
