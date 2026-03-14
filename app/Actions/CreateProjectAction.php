<?php

namespace App\Actions;

use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Validator;

class CreateProjectAction
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    public function execute(\App\DTOs\ProjectDTO $data): Project
    {
        return $this->projectService->create([
            'title'              => $data->title,
            'project_code'       => $data->projectCode,
            'project_type_id'    => $data->projectTypeId,
            'short_title'        => $data->shortTitle,
            'status'             => $data->status,
            'general_objectives' => $data->generalObjectives,
            'description'        => $data->description,
            'problem_analysis'   => $data->problemAnalysis,
            'strategy'           => $data->strategy,
            'justification'      => $data->justification,
            'start_date'         => $data->startDate,
            'end_date'           => $data->endDate,
        ]);
    }
}
