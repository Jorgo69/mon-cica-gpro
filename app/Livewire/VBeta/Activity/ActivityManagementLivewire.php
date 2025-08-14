<?php

namespace App\Livewire\VBeta\Activity;

use App\Models\Activity;
use Livewire\Component;

class ActivityManagementLivewire extends Component
{
    public string $activityId;
    // ... propriétés existantes
    public $activity;
    public $resources; // La liste des ressources de l'activité
    public $showModal = false; // Propriété pour gérer l'état de la modale

     // Écouteur pour l'événement 'resourceSaved'
    protected $listeners = ['resourceSaved' => 'refreshResources'];

    public function mount($activityId)
    {
        $this->activity = Activity::with('responsibleUser', 'result.specificObjective.logicalFramework.project', 'resources.responsibleUser')->findOrFail($activityId);
        $this->resources = $this->activity->resources;
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function closeModalAndRefresh()
    {
        $this->closeModal();
        $this->refreshResources();
        session()->flash('success', 'Ressource(s) sauvegardée(s) avec succès !');
    }

    public function refreshResources()
    {
        // Recharge la relation pour mettre à jour la liste des ressources
        $this->resources = $this->activity->resources()->with('responsibleUser')->get();
    }

    public function render()
    {
        $activity = Activity::with('result.specificObjective.logicalFramework.project')
                    ->findOrFail($this->activityId);

        return view('livewire.v-beta.activity.activity-management-livewire', [
            'activity' => $activity,
        ]);
    }
}
