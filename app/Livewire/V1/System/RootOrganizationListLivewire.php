<?php

namespace App\Livewire\V1\System;

use App\Enums\AccountType;
use App\Enums\OrganizationStatus;
use App\Models\Plan;
use App\Livewire\Traits\WithToastNotifications;
use App\Models\Organization;
use Livewire\Component;
use Livewire\WithPagination;

class RootOrganizationListLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function enterOrganization(string $id): void
    {
        $user = auth()->user();
        if ($user->role !== AccountType::ROOT) {
            abort(403);
        }

        $org = Organization::findOrFail($id);

        session([
            'acting_as_organization_id' => $org->id,
            'acting_as_organization_name' => $org->name,
        ]);
        setPermissionsTeamId($org->id);

        activity('root_access')
            ->causedBy($user)
            ->performedOn($org)
            ->withProperties([
                'organization_id' => $org->id,
                'organization_name' => $org->name,
                'action' => 'enter',
            ])
            ->log("Root Admin a accédé à l'espace {$org->name}");

        $this->redirect(route('dashboard'));
    }

    public function toggleStatus(string $id): void
    {
        $org = Organization::findOrFail($id);

        $org->status = $org->status === OrganizationStatus::ACTIVE
            ? OrganizationStatus::SUSPENDED
            : OrganizationStatus::ACTIVE;

        $org->save();

        $label = $org->status->label();
        $this->notifyToast('success', "Le statut de \"{$org->name}\" a été changé en : {$label}.", 'Statut modifié');
    }

    public function changePlan(string $orgId, string $planId, ?int $months = null): void
    {
        $org = Organization::findOrFail($orgId);
        $newPlan = Plan::find($planId);

        if (! $newPlan) return;

        $org->update([
            'plan_id' => $newPlan->id,
            'plan_activated_at' => now(),
            'plan_expires_at' => $months ? now()->addMonths($months) : null,
        ]);

        activity('plan_change')
            ->causedBy(auth()->user())
            ->performedOn($org)
            ->withProperties(['plan' => $newPlan->slug, 'months' => $months])
            ->log("Plan change en {$newPlan->label()} pour {$org->name}");

        $this->notifyToast('success', __('plans.plan_updated', ['org' => $org->name, 'plan' => $newPlan->label()]));
    }

    public function render()
    {
        return view('livewire.v1.system.root-organization-list-livewire', [
            'organizations' => Organization::query()
                ->withCount(['users', 'projects'])
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orderBy('name')
                ->paginate(15),
        ]);
    }
}
