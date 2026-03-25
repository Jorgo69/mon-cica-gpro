<?php

namespace App\Livewire\VBeta\Project;

use App\Models\Activity;
use Livewire\Component;

class ProjectDashboardActivityShowLivewire extends Component
{
    // L'ID de l'activité que nous voulons afficher
    public $activityId;
    
    // Les données de l'activité une fois chargées
    public $activity = null;

    /**
     * Mounts the component, loading the activity from the database.
     * Cette méthode s'exécute une seule fois au montage du composant.
     *
     * @param string $activityId
     * @return void
     */
    public function mount($activityId)
    {
        // On charge l'activité avec toutes les relations nécessaires
        $this->activity = Activity::with('responsibleUser', 'resources.responsibleUser')->findOrFail($activityId);
    }
    
    /**
     * Emits an event to the parent component to close the modal.
     *
     * @return void
     */
    public function closeModal()
    {
        // Émet un événement à l'intention du composant parent
        $this->dispatch('closeActivityDetails');
    }
    
    /**
     * Renders the view for the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.project.dashboard-activity-show');
    }
}
