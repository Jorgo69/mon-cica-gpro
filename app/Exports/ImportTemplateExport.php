<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportTemplateExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(protected string $type = 'projects') {}

    public function headings(): array
    {
        return match ($this->type) {
            'projects' => [
                __('common.title'),
                __('common.code'),
                __('common.description'),
                __('common.status'),
                __('common.start_date'),
                __('common.end_date'),
                __('common.currency'),
            ],
            'activities' => [
                __('activities.description'),
                __('activities.responsible') . ' (email)',
                __('activities.status'),
                __('common.start_date'),
                __('common.end_date'),
                __('budgets.budget'),
                __('activities.progress') . ' (%)',
            ],
            default => [],
        };
    }

    public function array(): array
    {
        // Example row to guide the user
        return match ($this->type) {
            'projects' => [
                ['Mon projet exemple', 'PRJ-001', 'Description du projet...', 'Brouillon', '01/01/2026', '31/12/2026', 'XOF'],
            ],
            'activities' => [
                ['Formation des agriculteurs', 'user@example.com', 'En Attente', '01/02/2026', '31/03/2026', '500000', '0'],
            ],
            default => [],
        };
    }

    public function title(): string
    {
        return match ($this->type) {
            'projects' => __('import.projects'),
            'activities' => __('import.activities'),
            default => 'Import',
        };
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
            2 => ['font' => ['italic' => true, 'color' => ['rgb' => '999999']]],
        ];
    }
}
