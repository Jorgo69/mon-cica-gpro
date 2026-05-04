<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectReportNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ReportService
{
    public function generatePdf(Project $project): string
    {
        $action = new \App\Actions\PDF\GenerateProjectReportAction();
        return $action->execute($project->id);
    }

    public function generateDocx(Project $project): string
    {
        $project->loadMissing([
            'logicalFramework.specificObjectives.results.activities',
            'budgets',
            'expenses',
            'creator',
        ]);

        $phpWord = new PhpWord();
        $phpWord->getDefaultFontName('Arial');
        $phpWord->getDefaultFontSize(11);

        $section = $phpWord->addSection();

        // Title
        $section->addText(
            $project->title,
            ['bold' => true, 'size' => 18, 'color' => '333333'],
            ['alignment' => 'center']
        );
        $section->addText(
            __('reports.generated_at', ['date' => now()->format('d/m/Y')]),
            ['size' => 9, 'color' => '999999'],
            ['alignment' => 'center']
        );
        $section->addTextBreak(2);

        // Summary
        $this->addHeading($section, __('reports.summary'));
        $activities = $project->getAllActivities();
        $budgetPlanned = $project->budgets->sum('total_cost');
        $budgetSpent = $project->expenses->sum('amount');
        $progress = $project->calculateProjectProgress();

        $table = $section->addTable(['borderSize' => 1, 'borderColor' => 'CCCCCC']);
        $this->addTableRow($table, __('common.status'), $project->status->label());
        $this->addTableRow($table, __('reports.progress'), $progress . '%');
        $this->addTableRow($table, __('reports.total_activities'), (string) $activities->count());
        $this->addTableRow($table, __('reports.completed_activities'), (string) $activities->where('status', \App\Enums\ActivityStatus::COMPLETED)->count());
        $this->addTableRow($table, __('reports.overdue_activities'), (string) $activities->where('status', \App\Enums\ActivityStatus::OVERDUE)->count());
        $this->addTableRow($table, __('reports.planned_budget'), number_format($budgetPlanned, 0, ',', ' '));
        $this->addTableRow($table, __('reports.spent_budget'), number_format($budgetSpent, 0, ',', ' '));
        $this->addTableRow($table, __('reports.budget_execution'), $budgetPlanned > 0 ? round(($budgetSpent / $budgetPlanned) * 100) . '%' : 'N/A');
        $section->addTextBreak();

        // Description
        if ($project->description) {
            $this->addHeading($section, __('common.description'));
            $section->addText(strip_tags($project->description), ['size' => 10]);
            $section->addTextBreak();
        }

        // Logical Framework
        if ($project->logicalFramework) {
            $this->addHeading($section, __('projects.show.logframe'));
            $lf = $project->logicalFramework;

            if ($lf->general_objective) {
                $section->addText(__('projects.show.general_objective'), ['bold' => true, 'size' => 10]);
                $section->addText(strip_tags($lf->general_objective), ['size' => 10]);
                $section->addTextBreak();
            }

            foreach ($lf->specificObjectives as $i => $so) {
                $section->addText(__('projects.show.specific_objectives') . ' ' . ($i + 1), ['bold' => true, 'size' => 10]);
                $section->addText(strip_tags($so->description), ['size' => 10]);

                foreach ($so->results as $j => $result) {
                    $section->addText('  ' . __('projects.show.expected_results') . ' ' . ($j + 1) . ': ' . strip_tags($result->description), ['size' => 10]);

                    foreach ($result->activities as $k => $activity) {
                        $statusLabel = $activity->status?->label() ?? '';
                        $section->addText(
                            "    - {$activity->description} [{$statusLabel}] {$activity->progress_percentage}%",
                            ['size' => 9]
                        );
                    }
                }
                $section->addTextBreak();
            }
        }

        // Save
        $fileName = 'Rapport_' . Str::slug($project->title) . '_' . now()->format('YmdHis') . '.docx';
        $directory = 'exports/docx';

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $path = storage_path('app/public/' . $directory . '/' . $fileName);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return $path;
    }

    public function generateAndNotify(Project $project, string $format = 'pdf'): string
    {
        $path = $format === 'docx' ? $this->generateDocx($project) : $this->generatePdf($project);

        // Notify creator + org admins
        $recipients = collect();

        if ($project->creator) {
            $recipients->push($project->creator);
        }

        if ($project->organization_id) {
            $admins = User::where('organization_id', $project->organization_id)
                ->where('role', \App\Enums\AccountType::ORG_ADMIN)
                ->where('id', '!=', $project->creator_user_id)
                ->get();
            $recipients = $recipients->merge($admins);
        }

        $recipients->unique('id')->each(function ($user) use ($project, $format) {
            $user->notify(new ProjectReportNotification($project, $format));
        });

        return $path;
    }

    protected function addHeading($section, string $text): void
    {
        $section->addText(
            strtoupper($text),
            ['bold' => true, 'size' => 12, 'color' => '4F46E5']
        );
        $section->addTextBreak();
    }

    protected function addTableRow($table, string $label, string $value): void
    {
        $row = $table->addRow();
        $row->addCell(4000)->addText($label, ['bold' => true, 'size' => 10]);
        $row->addCell(5000)->addText($value, ['size' => 10]);
    }
}
