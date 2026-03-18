<?php

namespace App\Livewire\VBeta\System;

use App\Models\Organization;
use App\Livewire\Traits\WithToastNotifications;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizationManagementLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public $search = '';
    public $showModal = false;
    
    // Formulaire
    public $name, $slug, $description, $address, $phone, $email;
    public ?Organization $selectedOrg = null;

    protected $listeners = ['openOrganizationModal'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:organizations,slug' . ($this->selectedOrg ? ',' . $this->selectedOrg->id : ''),
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function updatedName($value)
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    public function openOrganizationModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'slug', 'description', 'address', 'phone', 'email', 'selectedOrg']);
        
        if ($id) {
            $this->selectedOrg = Organization::findOrFail($id);
            $this->name = $this->selectedOrg->name;
            $this->slug = $this->selectedOrg->slug;
            $this->description = $this->selectedOrg->description;
            $this->address = $this->selectedOrg->address;
            $this->phone = $this->selectedOrg->phone;
            $this->email = $this->selectedOrg->email;
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'slug', 'description', 'address', 'phone', 'email', 'selectedOrg']);
    }

    public function save()
    {
        $this->validate();

        Organization::updateOrCreate(
            ['id' => $this->selectedOrg?->id],
            [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
            ]
        );

        $this->closeModal();
        $this->notifyToast('success', "L'organisation a été enregistrée avec succès.", 'Organisation configurée');
    }

    public function deleteOrg($id)
    {
        $org = Organization::findOrFail($id);
        
        if ($org->users()->count() > 0 || $org->projects()->count() > 0) {
            $this->notifyToast('warning', 'Impossible de supprimer cette organisation : elle contient encore des membres ou des projets actifs.', 'Sécurité Critique');
            return;
        }

        $org->delete();
        $this->notifyToast('success', "L'organisation a été supprimée définitivement du système.", 'Organisation retirée');
    }

    public function render()
    {
        return view('livewire.v-beta.system.organization-management-livewire', [
            'organizations' => Organization::query()
                ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%')->orWhere('slug', 'like', '%' . $this->search . '%'))
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
