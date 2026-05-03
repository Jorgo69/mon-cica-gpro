<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProjectFullExport implements WithMultipleSheets
{
    public function __construct(protected Project $project) {}

    public function sheets(): array
    {
        return [
            new ProjectActivitiesExport($this->project),
            new ProjectBudgetExport($this->project),
            new ProjectIndicatorsExport($this->project),
        ];
    }
}
