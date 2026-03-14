<?php

namespace App\Queries;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectQueries
{
    /**
     * Get all active projects with essential relations.
     */
    public function getActiveProjects(): Collection
    {
        return Project::with(['projectType', 'creator'])
            ->where('status', 'actif')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Get projects for the current user.
     */
    public function getMyProjects(): Collection
    {
        return Project::with(['projectType'])
            ->where('creator_user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
