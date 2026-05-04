<?php

namespace App\Livewire\V1\System;

use App\Livewire\Traits\WithToastNotifications;
use App\Models\Plan;
use Illuminate\Support\Str;
use Livewire\Component;

class PlanManagementLivewire extends Component
{
    use WithToastNotifications;

    public bool $showModal = false;
    public ?string $editingId = null;

    // Form fields
    public string $name = '';
    public string $slug = '';
    public int $price = 0;
    public string $currency = 'FCFA';
    public string $billingPeriod = 'month';
    public int $maxProjects = 1;
    public int $maxMembers = 3;
    public array $features = [];
    public bool $isDefault = false;
    public bool $isActive = true;
    public int $sortOrder = 0;

    protected array $allFeatures = [
        'basic_export',
        'logframe',
        'pdf_export',
        'excel_export',
        'share_link',
        'templates',
        'indicators',
        'budget_tracking',
        'api_access',
        'priority_support',
        'multi_currency',
    ];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:50|unique:plans,slug,' . $this->editingId,
            'price' => 'required|integer|min:0',
            'currency' => 'required|string|max:10',
            'billingPeriod' => 'required|in:month,year',
            'maxProjects' => 'required|integer|min:-1',
            'maxMembers' => 'required|integer|min:-1',
            'isActive' => 'boolean',
            'isDefault' => 'boolean',
            'sortOrder' => 'integer|min:0',
        ];
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->sortOrder = Plan::max('sort_order') + 1;
        $this->showModal = true;
    }

    public function openEdit(string $id): void
    {
        $plan = Plan::findOrFail($id);
        $this->editingId = $plan->id;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->price = $plan->price;
        $this->currency = $plan->currency;
        $this->billingPeriod = $plan->billing_period;
        $this->maxProjects = $plan->max_projects;
        $this->maxMembers = $plan->max_members;
        $this->features = $plan->features ?? [];
        $this->isDefault = $plan->is_default;
        $this->isActive = $plan->is_active;
        $this->sortOrder = $plan->sort_order;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'price' => $this->price,
            'currency' => $this->currency,
            'billing_period' => $this->billingPeriod,
            'max_projects' => $this->maxProjects,
            'max_members' => $this->maxMembers,
            'features' => $this->features,
            'is_default' => $this->isDefault,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->isDefault) {
            Plan::where('is_default', true)->update(['is_default' => false]);
        }

        if ($this->editingId) {
            Plan::findOrFail($this->editingId)->update($data);
            $this->notifyToast('success', 'Plan mis à jour.');
        } else {
            Plan::create($data);
            $this->notifyToast('success', 'Plan créé.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(string $id): void
    {
        $plan = Plan::findOrFail($id);

        if ($plan->is_default) {
            $this->notifyToast('error', 'Le plan par défaut ne peut pas être désactivé.');
            return;
        }

        $plan->update(['is_active' => !$plan->is_active]);
        $this->notifyToast('success', $plan->is_active ? 'Plan activé.' : 'Plan désactivé.');
    }

    public function deletePlan(string $id): void
    {
        $plan = Plan::findOrFail($id);

        if ($plan->is_default) {
            $this->notifyToast('error', 'Le plan par défaut ne peut pas être supprimé.');
            return;
        }

        $plan->delete();
        $this->notifyToast('success', 'Plan supprimé.');
    }

    public function updatedName(): void
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($this->name);
        }
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
        $this->price = 0;
        $this->currency = 'FCFA';
        $this->billingPeriod = 'month';
        $this->maxProjects = 1;
        $this->maxMembers = 3;
        $this->features = [];
        $this->isDefault = false;
        $this->isActive = true;
        $this->sortOrder = 0;
    }

    public function render()
    {
        return view('livewire.v1.system.plan-management-livewire', [
            'plans' => Plan::ordered()->get(),
            'allFeatures' => $this->allFeatures,
        ]);
    }
}
