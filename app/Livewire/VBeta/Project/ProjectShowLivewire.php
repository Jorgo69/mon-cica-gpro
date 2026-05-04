<?php

namespace App\Livewire\VBeta\Project;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\LogicalFramework;
use App\Models\SpecificObjective;
use App\Models\Result;
use App\Models\Activity;
use App\Models\Risk;
use App\Models\Budget;
use App\Models\DynamicProjectField;
use App\Enums\LogframeDisplayFormat;
use App\Livewire\Traits\WithToastNotifications;

class ProjectShowLivewire extends Component
{
    use AuthorizesRequests, WithToastNotifications;

    public $projectId;
    public $project;
    public $dynamicFormFields = [];
    public $activeTab = 'overview'; // [overview, logframe, documents, history]
    public $logframeFormat = 'cards'; // default format

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setLogframeFormat(string $format)
    {
        if (LogframeDisplayFormat::tryFrom($format)) {
            $this->logframeFormat = $format;
        }
    }

    public function toggleTemplate()
    {
        if (! $this->project) return;

        $this->project->update(['is_template' => ! $this->project->is_template]);

        $message = $this->project->is_template
            ? __('projects.templates.marked')
            : __('projects.templates.unmarked');

        $this->notifyToast('success', $message);
    }

    /**
     * Monte le composant avec l'ID du projet.
     * Utilise l'eager loading pour charger toutes les relations nécessaires.
     */
    public function mount($projectId)
    {
        $this->projectId = $projectId;
        
        
        $this->loadProject();
        $this->authorize('view', $this->project);
    }

    /**
     * Charge le projet et toutes ses relations.
     *
     * J'ai mis à jour les relations pour qu'elles correspondent à votre modèle
     * et à votre formulaire de création/édition.
     */
    public function loadProject()
    {
        $this->project = \App\Services\Queries\LogframeQueryService::forProject($this->projectId)->project();

        if (!$this->project) {
            abort(404);
        }

        // Charge les définitions des champs dynamiques pour l'affichage
        if ($this->project->projectType) {
             $this->dynamicFormFields = $this->project->projectType->dynamicFields()
                ->orderBy('order')
                ->get()
                ->groupBy('section')
                ->toArray();
        }
    }

    

    // ─── AI Summary ──────────────────────────────────────────
    public ?string $aiSummary = null;

    public function aiGenerateSummary()
    {
        if (!$this->project) return;

        $ai = app(\App\Services\AI\AiService::class);
        if (!$ai::isConfigured()) return;

        $activities = $this->project->getAllActivities();
        $budgetPlanned = $this->project->budgets->sum('total_cost');
        $budgetSpent = $this->project->expenses->sum('amount');

        $this->aiSummary = $ai->generateExecutiveSummary([
            'title' => $this->project->title,
            'status' => $this->project->status?->label() ?? 'N/A',
            'progress' => $this->project->calculateProjectProgress(),
            'total_activities' => $activities->count(),
            'completed_activities' => $activities->where('status', \App\Enums\ActivityStatus::COMPLETED)->count(),
            'overdue_activities' => $activities->where('status', \App\Enums\ActivityStatus::OVERDUE)->count(),
            'planned_budget' => number_format($budgetPlanned, 0, ',', ' '),
            'spent_budget' => number_format($budgetSpent, 0, ',', ' '),
            'start_date' => $this->project->start_date?->format('d/m/Y') ?? 'N/A',
            'end_date' => $this->project->end_date?->format('d/m/Y') ?? 'N/A',
        ]);

        if (!$this->aiSummary) {
            $this->notifyToast('danger', __('ai.error'));
        }
    }

    // ─── Project Members ───────────────────────────────────

    public string $memberSearchQuery = '';

    public function addProjectMember(string $userId): void
    {
        $user = auth()->user();
        if (!$this->canManageMembers($user)) {
            $this->notifyToast('error', __('common.unauthorized'));
            return;
        }

        $member = \App\Models\User::where('organization_id', $this->project->organization_id)
            ->where('id', $userId)
            ->first();

        if (!$member) return;

        $this->project->addMember($member);
        $this->memberSearchQuery = '';
        $this->notifyToast('success', __('projects.members.added', ['name' => $member->name]));
    }

    public function removeProjectMember(string $userId): void
    {
        $user = auth()->user();
        if (!$this->canManageMembers($user)) {
            $this->notifyToast('error', __('common.unauthorized'));
            return;
        }

        // Cannot remove the creator
        if ($userId === $this->project->creator_user_id) {
            $this->notifyToast('error', __('projects.members.cannot_remove_creator'));
            return;
        }

        $member = \App\Models\User::find($userId);
        if ($member) {
            $this->project->removeMember($member);
            $this->notifyToast('success', __('projects.members.removed', ['name' => $member->name]));
        }
    }

    protected function canManageMembers(\App\Models\User $user): bool
    {
        return $user->role === \App\Enums\AccountType::ORG_ADMIN
            || $user->id === $this->project->creator_user_id;
    }

    /**
     * Rend la vue du composant.
     */
    public function render()
    {
        $projectMembers = $this->project->members()->get();

        $availableMembers = collect();
        if ($this->memberSearchQuery && $this->project->organization_id) {
            $existingIds = $projectMembers->pluck('id')->toArray();
            $availableMembers = \App\Models\User::where('organization_id', $this->project->organization_id)
                ->whereNotIn('id', $existingIds)
                ->where(fn ($q) => $q->where('name', 'like', "%{$this->memberSearchQuery}%")
                    ->orWhere('email', 'like', "%{$this->memberSearchQuery}%"))
                ->limit(5)
                ->get(['id', 'name', 'email']);
        }

        return view('livewire.project.show', [
            'projectMembers' => $projectMembers,
            'availableMembers' => $availableMembers,
        ]);
    }
}
