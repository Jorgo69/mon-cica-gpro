<?php

namespace App\Exports;

use App\Models\Indicator;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProjectIndicatorsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(protected Project $project) {}

    public function collection()
    {
        $this->project->loadMissing(
            'logicalFramework.indicatorItems',
            'logicalFramework.specificObjectives.indicatorItems',
            'logicalFramework.specificObjectives.results.indicatorItems',
        );

        $indicators = collect();

        if ($lf = $this->project->logicalFramework) {
            foreach ($lf->indicatorItems as $ind) {
                $ind->_level = __('projects.show.general_objective');
                $ind->_parent = $lf->general_objective ?? '';
                $indicators->push($ind);
            }

            foreach ($lf->specificObjectives as $so) {
                foreach ($so->indicatorItems as $ind) {
                    $ind->_level = __('projects.show.specific_objectives');
                    $ind->_parent = $so->description ?? '';
                    $indicators->push($ind);
                }

                foreach ($so->results as $result) {
                    foreach ($result->indicatorItems as $ind) {
                        $ind->_level = __('projects.show.expected_results');
                        $ind->_parent = $result->description ?? '';
                        $indicators->push($ind);
                    }
                }
            }
        }

        return $indicators;
    }

    public function headings(): array
    {
        return [
            __('common.level'),
            __('common.parent'),
            __('common.description'),
            __('common.baseline'),
            __('common.target'),
            __('common.verification_source'),
            __('common.assumption'),
        ];
    }

    public function map($indicator): array
    {
        return [
            $indicator->_level ?? '',
            strip_tags($indicator->_parent ?? ''),
            strip_tags($indicator->description ?? ''),
            $indicator->baseline_value ?? '',
            $indicator->target_value ?? '',
            $indicator->verification_source ?? '',
            $indicator->assumption ?? '',
        ];
    }

    public function title(): string
    {
        return __('common.indicators');
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
