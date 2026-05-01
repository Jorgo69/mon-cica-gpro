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

class ProjectBudgetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(protected Project $project) {}

    public function collection()
    {
        $this->project->loadMissing('budgets', 'expenses');

        return $this->project->budgets->map(function ($budget) {
            $spent = $this->project->expenses
                ->where('budget_id', $budget->id)
                ->sum('amount');

            $budget->_spent = $spent;
            $budget->_remaining = ($budget->total_cost ?? 0) - $spent;

            return $budget;
        });
    }

    public function headings(): array
    {
        return [
            __('budgets.description'),
            __('budgets.category'),
            __('budgets.quantity'),
            __('budgets.unit_cost'),
            __('budgets.planned'),
            __('budgets.spent'),
            __('budgets.remaining'),
            __('budgets.usage_percent'),
        ];
    }

    public function map($budget): array
    {
        $planned = (float) ($budget->total_cost ?? 0);
        $spent = (float) $budget->_spent;
        $percent = $planned > 0 ? round(($spent / $planned) * 100, 1) : 0;

        return [
            strip_tags($budget->description ?? ''),
            $budget->category ?? '—',
            $budget->quantity ?? '',
            number_format((float) ($budget->unit_cost ?? 0), 0, ',', ' '),
            number_format($planned, 0, ',', ' '),
            number_format($spent, 0, ',', ' '),
            number_format($budget->_remaining, 0, ',', ' '),
            $percent . '%',
        ];
    }

    public function title(): string
    {
        return __('budgets.title');
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
