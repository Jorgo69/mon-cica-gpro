<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProjectActivitiesExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(protected Project $project) {}

    public function collection()
    {
        $this->project->loadMissing('logicalFramework.specificObjectives.results.activities.responsibleUser');

        $activities = collect();

        if ($this->project->logicalFramework) {
            foreach ($this->project->logicalFramework->specificObjectives as $so) {
                foreach ($so->results as $result) {
                    foreach ($result->activities as $activity) {
                        $activity->_so_description = $so->description;
                        $activity->_result_description = $result->description;
                        $activities->push($activity);
                    }
                }
            }
        }

        return $activities;
    }

    public function headings(): array
    {
        return [
            __('activities.objective'),
            __('activities.result'),
            __('activities.description'),
            __('activities.responsible'),
            __('activities.status'),
            __('activities.progress'),
            __('activities.start_date'),
            __('activities.end_date'),
            __('budgets.budget'),
        ];
    }

    public function map($activity): array
    {
        return [
            strip_tags($activity->_so_description ?? ''),
            strip_tags($activity->_result_description ?? ''),
            strip_tags($activity->description ?? ''),
            $activity->responsibleUser?->name ?? '—',
            $activity->status?->label() ?? '',
            $activity->progress_percentage . '%',
            $activity->start_date?->format('d/m/Y') ?? '',
            $activity->end_date?->format('d/m/Y') ?? '',
            number_format((float) $activity->budget, 0, ',', ' '),
        ];
    }

    public function title(): string
    {
        return __('activities.title');
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
