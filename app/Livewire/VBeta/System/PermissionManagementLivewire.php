<?php

namespace App\Livewire\VBeta\System;

use App\Models\Permission;
use App\Livewire\Traits\WithToastNotifications;
use Livewire\Component;
use Livewire\WithPagination;

class PermissionManagementLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public $search = '';
    public $showModal = false;
    public $name;
    public ?Permission $selectedPermission = null;

    protected $listeners = ['openPermissionModal'];

    protected $rules = [
        'name' => 'required|string|max:255|unique:permissions,name',
    ];

    public function openPermissionModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'selectedPermission']);
        
        if ($id) {
            $this->selectedPermission = Permission::findOrFail($id);
            $this->name = $this->selectedPermission->name;
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'selectedPermission']);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:permissions,name' . ($this->selectedPermission ? ',' . $this->selectedPermission->id : ''),
        ]);

        Permission::updateOrCreate(
            ['id' => $this->selectedPermission?->id],
            ['name' => $this->name, 'guard_name' => 'web']
        );

        $this->closeModal();
        $this->notifyToast('success', 'La permission a été mise à jour.', 'Permission enregistrée');
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        
        // Liste des permissions critiques à ne jamais supprimer
        $protected = ['manage-users', 'manage-organization', 'access-admin-panel'];
        if (in_array($permission->name, $protected)) {
            $this->notifyToast('error', 'Cette permission système est protégée et ne peut être supprimée.', 'Sécurité Système');
            return;
        }

        $permission->delete();
        $this->notifyToast('success', 'La permission a été supprimée définitivement.', 'Action effectuée');
    }

    
    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render()
    {
        return view('livewire.v-beta.system.permission-management-livewire', [
            'permissions' => Permission::query()
                ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                ->orderBy('name')
                ->paginate(15),
        ]);
    }
}
