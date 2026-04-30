<?php

namespace App\Livewire\VBeta\System;

use App\Enums\AccountType;
use App\Enums\OrganizationStatus;
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

    public function render()
    {
        return view('livewire.v-beta.system.root-organization-list-livewire', [
            'organizations' => Organization::query()
                ->withCount(['users', 'projects'])
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orderBy('name')
                ->paginate(15),
        ]);
    }
}
