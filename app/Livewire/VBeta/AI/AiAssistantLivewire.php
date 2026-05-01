<?php

namespace App\Livewire\VBeta\AI;

use App\Livewire\Traits\WithToastNotifications;
use App\Services\AI\GeminiService;
use Livewire\Component;

class AiAssistantLivewire extends Component
{
    use WithToastNotifications;

    public string $context = '';   // 'description', 'logframe', 'summary'
    public string $projectTitle = '';
    public string $projectDescription = '';
    public ?string $projectId = null;

    public ?string $result = null;
    public ?array $logframeSuggestion = null;
    public bool $loading = false;

    public function generateDescription()
    {
        $this->loading = true;
        $this->result = null;

        $ai = app(GeminiService::class);
        $this->result = $ai->generateProjectDescription($this->projectTitle, $this->projectDescription ?: null);

        $this->loading = false;

        if (!$this->result) {
            $this->notifyToast('danger', __('ai.error'));
        }
    }

    public function suggestLogframe()
    {
        $this->loading = true;
        $this->logframeSuggestion = null;

        $ai = app(GeminiService::class);
        $this->logframeSuggestion = $ai->suggestLogframe($this->projectTitle, $this->projectDescription);

        $this->loading = false;

        if (!$this->logframeSuggestion) {
            $this->notifyToast('danger', __('ai.error'));
        }
    }

    public function generateSummary()
    {
        if (!$this->projectId) return;

        $this->loading = true;
        $this->result = null;

        $project = \App\Models\Project::find($this->projectId);
        if (!$project) {
            $this->loading = false;
            return;
        }

        $activities = $project->getAllActivities();
        $budgetPlanned = $project->budgets->sum('total_cost');
        $budgetSpent = $project->expenses->sum('amount');

        $ai = app(GeminiService::class);
        $this->result = $ai->generateExecutiveSummary([
            'title' => $project->title,
            'status' => $project->status?->label() ?? 'N/A',
            'progress' => $project->calculateProjectProgress(),
            'total_activities' => $activities->count(),
            'completed_activities' => $activities->where('status', \App\Enums\ActivityStatus::COMPLETED)->count(),
            'overdue_activities' => $activities->where('status', \App\Enums\ActivityStatus::OVERDUE)->count(),
            'planned_budget' => number_format($budgetPlanned, 0, ',', ' '),
            'spent_budget' => number_format($budgetSpent, 0, ',', ' '),
            'start_date' => $project->start_date?->format('d/m/Y') ?? 'N/A',
            'end_date' => $project->end_date?->format('d/m/Y') ?? 'N/A',
        ]);

        $this->loading = false;

        if (!$this->result) {
            $this->notifyToast('danger', __('ai.error'));
        }
    }

    public function applyDescription()
    {
        $this->dispatch('ai-description-applied', description: $this->result);
        $this->notifyToast('success', __('ai.applied'));
    }

    public function applyLogframe()
    {
        $this->dispatch('ai-logframe-applied', logframe: $this->logframeSuggestion);
        $this->notifyToast('success', __('ai.applied'));
    }

    public function render()
    {
        return view('livewire.v-beta.ai.ai-assistant-livewire');
    }
}
