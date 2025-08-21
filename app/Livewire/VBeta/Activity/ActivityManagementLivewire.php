<?php

namespace App\Livewire\VBeta\Activity;

use App\Models\Activity;
use Livewire\Component;

class ActivityManagementLivewire extends Component
{
    public string $activityId;
    public $activity;
    public $resources, $subActivities;
    public $showModal, $showModalForSubActivity = false;
    public $editingResourceId, $editingSubActivityId = null; // ID de la ressource en cours d'édition

    // Écouteur pour l'événement 'resourceSaved'
    protected $listeners = [
        'resourceSaved' => 'closeModalAndRefresh',
        'subActivitySaved' => 'closeModalAndRefresh',
    ];

    public function mount($activityId)
    {
        $this->activity = Activity::with('responsibleUser', 'result.specificObjective.logicalFramework.project', 'resources.responsibleUser')->findOrFail($activityId);
        $this->resources = $this->activity->resources;
        $this->subActivities = $this->activity->subActivities;
    }

    /**
     * Ouvre la modale pour la création ou l'édition d'une ressource.
     * @param string|null $resourceId L'ID de la ressource à éditer, si applicable.
     */
    public function openModal(?string $resourceId = null)
    {
        $this->editingResourceId = $resourceId;
        $this->showModal = true;
    }

    public function openModalForSubActivity(?string $subActivityId = null)
    {
        $this->editingSubActivityId = $subActivityId;
        $this->showModalForSubActivity = true;
    }

    /**
     * Ferme la modale et réinitialise l'état.
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->editingResourceId = null;
    }

    public function closeModalForSubActivity()
    {
        $this->showModalForSubActivity = false;
        $this->editingSubActivityId = null;
    }

    /**
     * Ferme la modale et rafraîchit la liste des ressources.
     */
    public function closeModalAndRefresh()
    {
        $this->closeModal();
        $this->refreshResources();
        session()->flash('success', 'Ressource(s) sauvegardée(s) avec succès !');
    }

    public function closeModalAndRefreshForSubActivity()
    {
        $this->closeModalForSubActivity();
        $this->refreshSubActivities();
        session()->flash('success', 'Sous Activities sauvegardée(s) avec succès !');
    }

    public function refreshResources()
    {
        // Recharge la relation pour mettre à jour la liste des ressources
        $this->resources = $this->activity->resources()->with('responsibleUser')->get();
    }

    public function refreshSubActivities()
    {
        // Recharge la relation pour mettre à jour la liste des ressources
        $this->subActivities = $this->activity->subActivities()->with('responsibleUser')->get();
    }

    
    
    public function render()
    {
        return view('livewire.v-beta.activity.activity-management-livewire');
    }
}