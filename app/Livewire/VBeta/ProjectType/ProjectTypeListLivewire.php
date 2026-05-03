<?php

namespace App\Livewire\VBeta\ProjectType;

use App\Models\ProjectType;
use App\Services\OrgContext;
use App\Livewire\Traits\WithToastNotifications;
use Livewire\Component;

class ProjectTypeListLivewire extends Component
{
    use WithToastNotifications;

    public $projectTypes;

    public function mount()
    {
        $this->loadTypes();
    }

    public function deleteProjectType($id)
    {
        $type = ProjectType::findOrFail($id);

        if ($type->is_system) {
            $this->notifyToast('error', 'Les types systeme ne peuvent pas etre supprimes.');
            return;
        }

        if ($type->organization_id && $type->organization_id !== OrgContext::orgId()) {
            $this->notifyToast('error', 'Vous ne pouvez pas supprimer ce type.');
            return;
        }

        $type->delete();
        $this->loadTypes();
        $this->notifyToast('success', 'Type de projet supprime.');
    }

    public function toggleActive($id)
    {
        $type = ProjectType::findOrFail($id);

        // Only ROOT can toggle system types
        if ($type->is_system && !OrgContext::isRoot()) {
            $this->notifyToast('error', 'Seul l\'administrateur systeme peut modifier les types systeme.');
            return;
        }

        // Org types: only own org or ROOT
        if ($type->organization_id && $type->organization_id !== OrgContext::orgId() && !OrgContext::isRoot()) {
            $this->notifyToast('error', 'Vous ne pouvez pas modifier ce type.');
            return;
        }

        $type->update(['is_active' => !$type->is_active]);
        $this->loadTypes();
        $this->notifyToast('success', $type->is_active ? 'Type active.' : 'Type masque.');
    }

    private function loadTypes()
    {
        $orgId = OrgContext::orgId();

        $query = ProjectType::with('dynamicFields');

        if (OrgContext::isRoot() && !OrgContext::isImpersonating()) {
            $this->projectTypes = $query->orderBy('is_system', 'desc')->orderBy('name')->get();
        } else {
            $this->projectTypes = $query->visibleForOrg($orgId)->orderBy('is_system', 'desc')->orderBy('name')->get();
        }
    }

    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render()
    {
        return view('livewire.project-type.list');
    }
}
