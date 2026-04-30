<?php

namespace App\Livewire\VBeta\Budget;

use App\Livewire\Traits\WithToastNotifications;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Project;
use App\Notifications\BudgetThresholdNotification;
use App\Services\BudgetTrackingService;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseManagementLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public string $projectId;
    public bool $showModal = false;
    public ?string $editingId = null;

    // Form
    #[Validate('required|string|max:255')]
    public string $description = '';

    #[Validate('required|numeric|gt:0')]
    public string $amount = '';

    #[Validate('required|date')]
    public string $expense_date = '';

    #[Validate('nullable|exists:budgets,id')]
    public ?string $budget_id = null;

    #[Validate('nullable|string|max:100')]
    public string $category = '';

    #[Validate('nullable|string|max:100')]
    public string $reference = '';

    #[Validate('nullable|string|max:500')]
    public string $notes = '';

    public function mount(string $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function openModal(?string $id = null): void
    {
        $this->resetValidation();

        if ($id) {
            $expense = Expense::findOrFail($id);
            $this->editingId = $id;
            $this->description = $expense->description;
            $this->amount = (string) $expense->amount;
            $this->expense_date = $expense->expense_date->format('Y-m-d');
            $this->budget_id = $expense->budget_id;
            $this->category = $expense->category ?? '';
            $this->reference = $expense->reference ?? '';
            $this->notes = $expense->notes ?? '';
        } else {
            $this->editingId = null;
            $this->description = '';
            $this->amount = '';
            $this->expense_date = now()->format('Y-m-d');
            $this->budget_id = null;
            $this->category = '';
            $this->reference = '';
            $this->notes = '';
        }

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'project_id' => $this->projectId,
            'description' => $this->description,
            'amount' => (float) $this->amount,
            'expense_date' => $this->expense_date,
            'budget_id' => $this->budget_id ?: null,
            'category' => $this->category ?: null,
            'reference' => $this->reference ?: null,
            'notes' => $this->notes ?: null,
        ];

        if ($this->editingId) {
            Expense::findOrFail($this->editingId)->update($data);
            $this->notifyToast('success', 'Depense mise a jour.');
        } else {
            Expense::create($data);
            $this->notifyToast('success', 'Depense enregistree.');
        }

        $this->showModal = false;

        // Check budget threshold
        $this->checkBudgetAlert();
    }

    public function delete(string $id): void
    {
        Expense::findOrFail($id)->delete();
        $this->notifyToast('success', 'Depense supprimee.');
    }

    public function render()
    {
        $project = Project::findOrFail($this->projectId);
        $summary = BudgetTrackingService::projectSummary($project);
        $lineSummaries = BudgetTrackingService::budgetLineSummaries($project);
        $burnRate = BudgetTrackingService::burnRate($project);

        $expenses = Expense::where('project_id', $this->projectId)
            ->with(['budget', 'creator'])
            ->orderByDesc('expense_date')
            ->paginate(15);

        $budgets = $project->budgets()->get();

        return view('livewire.v-beta.budget.expense-management-livewire', [
            'project' => $project,
            'summary' => $summary,
            'lineSummaries' => $lineSummaries,
            'burnRate' => $burnRate,
            'expenses' => $expenses,
            'budgets' => $budgets,
        ]);
    }

    private function checkBudgetAlert(): void
    {
        $project = Project::find($this->projectId);
        if (!$project) return;

        $summary = BudgetTrackingService::projectSummary($project);

        if ($summary['used_percent'] >= 80 && $project->creator) {
            $project->creator->notify(new BudgetThresholdNotification($project, $summary['used_percent']));
        }
    }
}
