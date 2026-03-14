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

    public function execute(\App\DTOs\ProjectData $data): Project
    {
        // The DTO ensures the structure is correct. We can pass it to the service.
        return $this->projectService->create((array) $data);
    }
}
