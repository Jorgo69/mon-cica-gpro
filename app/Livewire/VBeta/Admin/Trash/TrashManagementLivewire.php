<?php

namespace App\Livewire\VBeta\Admin\Trash;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectType;

class TrashManagementLivewire extends Component
{
    use WithPagination;

    public $selectedIds = [];
    public $showModal = false;
    public $modalType = '';
    public $selectedItem = null;
    public $selectedModel = null; // "user", "project", "project_type"

    // Ouvrir modal
    public function openModal($type, $model, $id = null)
    {
        $this->modalType = $type;
        $this->selectedModel = $model;

        if ($id) {
            if ($model === 'user') {
                $this->selectedItem = User::onlyTrashed()->findOrFail($id);
            } elseif ($model === 'project') {
                $this->selectedItem = Project::onlyTrashed()->findOrFail($id);
            } elseif ($model === 'project_type') {
                $this->selectedItem = ProjectType::onlyTrashed()->findOrFail($id);
            }
        }

        $this->showModal = true;
    }

    // Restaurer un élément
    public function restore($id = null, $model = null)
    {
        if ($id && $model) {
            $this->restoreItems([$id], $model);
        } elseif (!empty($this->selectedIds)) {
            $this->restoreItems($this->selectedIds, $this->selectedModel);
        }

        $this->resetSelection();
    }

    // Supprimer définitivement
    public function forceDelete($id = null, $model = null)
    {
        if ($id && $model) {
            $this->deleteItems([$id], $model);
        } elseif (!empty($this->selectedIds)) {
            $this->deleteItems($this->selectedIds, $this->selectedModel);
        }

        $this->resetSelection();
    }

    private function restoreItems($ids, $model)
    {
        switch ($model) {
            case 'user':
                User::onlyTrashed()->whereIn('id', $ids)->restore();
                break;
            case 'project':
                Project::onlyTrashed()->whereIn('id', $ids)->restore();
                break;
            case 'project_type':
                ProjectType::onlyTrashed()->whereIn('id', $ids)->restore();
                break;
        }
    }

    private function deleteItems($ids, $model)
    {
        switch ($model) {
            case 'user':
                User::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                break;
            case 'project':
                Project::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                break;
            case 'project_type':
                ProjectType::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                break;
        }
    }

    private function resetSelection()
    {
        $this->selectedIds = [];
        $this->selectedItem = null;
        $this->selectedModel = null;
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.admin.trash.management', [
            'trashedUsers' => User::onlyTrashed()->paginate(5, ['*'], 'users'),
            'trashedProjects' => Project::onlyTrashed()->paginate(5, ['*'], 'projects'),
            'trashedProjectTypes' => ProjectType::onlyTrashed()->paginate(5, ['*'], 'project_types'),
        ]);
    }
}
