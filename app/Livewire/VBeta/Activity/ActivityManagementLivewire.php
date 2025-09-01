<?php

namespace App\Livewire\VBeta\Activity;

use App\Models\GeneralAdministration;
use App\Models\Activity;
use App\Models\SubActivity;
use Livewire\Component;

class ActivityManagementLivewire extends Component
{
    public string $activityId;
    public $activity;
    public $resources, $subActivities;
    public $showModal, $showModalForSubActivity = false;
    public $editingResourceId, $editingSubActivityId = null; // ID de la ressource en cours d'édition
    public $projectCategories,$generalAdministration, $projectTypes = [];
    public $subActivityStatuses = [];


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
        
        // $this->projectCategories = GeneralAdministration::where('type', 'project_type_category')->get();

        $this->projectTypes = GeneralAdministration::where('type', 'activity_status')->get();

        // ⚡ Préremplir les statuts des sous-activités
        // foreach ($this->subActivities as $sub) {
        //     $this->subActivityStatuses[$sub->id] = $sub->status;
        // }
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

    public function updatedSubActivityStatuses($value, $subActivityId)
    {
        $subActivity = SubActivity::find($subActivityId);

        if ($subActivity) {
            $subActivity->update(['status' => $value]);
            // Optionnel : émettre un événement pour prévenir que le statut a changé
            $this->dispatch('projectStatusUpdated', subActivityId: $subActivityId, status: $value);
        }
    }

    
    
    public function render()
    {
        return view('livewire.v-beta.activity.activity-management-livewire');
    }
}