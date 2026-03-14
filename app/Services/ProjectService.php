<?php

namespace App\Services;

use App\Models\Project;
use App\Enums\ProjectStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    /**
     * Create a new project.
     */
    public function create(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            $project = Project::create([
                'id' => (string) Str::uuid(),
                'title' => $data['title'],
                'project_code' => $data['project_code'] ?? $this->generateProjectCode(),
                'project_type_id' => $data['project_type_id'],
                'creator_user_id' => auth()->id(),
                'status' => $data['status'] ?? ProjectStatus::DRAFT->value,
                'short_title' => $data['short_title'] ?? null,
                'general_objectives' => $data['general_objectives'] ?? null,
                'description' => $data['description'] ?? null,
                'problem_analysis' => $data['problem_analysis'] ?? null,
                'strategy' => $data['strategy'] ?? null,
                'justification' => $data['justification'] ?? null,
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
            ]);

            return $project;
        });
    }

    /**
     * Update an existing project.
     */
    public function update(Project $project, array $data): Project
    {
        $project->update($data);
        return $project;
    }

    /**
     * Generate a unique project code.
     */
    protected function generateProjectCode(): string
    {
        $year = date('Y');
        $count = Project::whereYear('created_at', $year)->count() + 1;
        return sprintf('PRJ-%s-%04d', $year, $count);
    }
}
