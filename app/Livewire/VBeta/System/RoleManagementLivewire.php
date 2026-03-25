<?php

namespace App\Livewire\VBeta\System;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Organization;
use App\Livewire\Traits\WithToastNotifications;
use Livewire\Component;
use Livewire\WithPagination;

class RoleManagementLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    // Filtres
    public $search = '';
    public $organizationId = null; // null = rôles globaux

    // Gestion de la modale
    public $showModal = false;
    public $modalType = 'create'; // create, edit, delete
    public ?Role $selectedRole = null;

    // Formulaire
    public $name;
    public $org_id; // id de l'organisation pour le nouveau rôle
    public $rolePermissions = []; // ids des permissions sélectionnées

    protected $listeners = ['openRoleModal'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'org_id' => 'nullable|exists:organizations,id',
        'rolePermissions' => 'array',
    ];

    public function openRoleModal($id = null)
    {
        $this->resetValidation();
        $this->resetForm();
        
        if ($id) {
            $this->modalType = 'edit';
            $this->selectedRole = Role::findOrFail($id);
            $this->name = $this->selectedRole->name;
            $this->org_id = $this->selectedRole->organization_id;
            $this->rolePermissions = $this->selectedRole->permissions->pluck('id')->toArray();
        } else {
            $this->modalType = 'create';
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        $role = Role::updateOrCreate(
            ['id' => $this->selectedRole?->id],
            [
                'name' => $this->name,
                'organization_id' => $this->org_id,
                'guard_name' => 'web'
            ]
        );

        $role->syncPermissions($this->rolePermissions);

        $this->closeModal();
        $this->notifyToast('success', 'Le rôle a été enregistré avec succès.', 'Rôle configuré');
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        
        // Empêcher la suppression des rôles critiques (optionnel, selon besoins)
        if (in_array($role->name, ['IT_ADMIN', 'ORG_ADMIN'])) {
            $this->notifyToast('error', 'Ce rôle est critique pour le système et ne peut être supprimé.', 'Action Bloquée');
            return;
        }

        $role->delete();
        $this->notifyToast('success', 'Le rôle a été supprimé du système.', 'Rôle supprimé');
    }

    private function resetForm()
    {
        $this->reset(['name', 'org_id', 'rolePermissions', 'selectedRole', 'modalType']);
    }

    public function render()
    {
        $roles = Role::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->organizationId !== null, fn($q) => $q->where('organization_id', $this->organizationId))
            ->orderBy('organization_id')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.system.roles', [
            'roles' => $roles,
            'organizations' => Organization::all(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }
}
