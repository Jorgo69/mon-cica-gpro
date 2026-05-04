<?php

namespace App\Livewire\V1\Activity;

use App\Models\Activity;
use App\Models\SubActivity;
use App\Models\Resource;
use App\Queries\ActivityQueries;
use App\Actions\SubActivities\UpdateSubActivityStatusAction;
use App\DTOs\SubActivityDTO;
use App\Livewire\Traits\WithToastNotifications;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class ActivityManagementLivewire extends Component
{
    use WithToastNotifications;

    public string $activityId;
    public $activity;
    public $resources, $subActivities;
    public $showModal = false;
    public $showModalForSubActivity = false;
    public $editingResourceId = null;
    public $editingSubActivityId = null;
    public $subActivityStatuses = [];

    protected $listeners = [
        'resourceSaved' => 'closeModalAndRefresh',
        'subActivitySaved' => 'closeModalAndRefreshForSubActivity',
    ];

    /**
     * Mount the component and load activity details via Query.
     */
    public function mount(string $activityId, ActivityQueries $queries)
    {
        $this->activityId = $activityId;
        $this->loadActivity($queries);
    }

    /**
     * Load activity and its relations.
     */
    private function loadActivity(ActivityQueries $queries)
    {
        $this->activity = $queries->findActivityWithDetails($this->activityId);
        
        // Authorization check
        Gate::authorize('view', $this->activity);

        $this->resources = $this->activity->resources;
        $this->subActivities = $this->activity->children;
        
        foreach ($this->subActivities as $sub) {
            $this->subActivityStatuses[$sub->id] = $sub->status instanceof \App\Enums\ActivityStatus 
                ? $sub->status->value 
                : (string) $sub->status;
        }
    }

    public function getActivityStatusesProperty()
    {
        return \App\Enums\ActivityStatus::cases();
    }

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

    public function closeModalAndRefresh(ActivityQueries $queries)
    {
        $this->closeModal();
        $this->loadActivity($queries);
        $this->notifyToast('success', 'Ressource sauvegardée avec succès !');
    }

    public function closeModalAndRefreshForSubActivity(ActivityQueries $queries)
    {
        $this->closeModalForSubActivity();
        $this->loadActivity($queries);
        $this->notifyToast('success', 'Sous-activité sauvegardée avec succès !');
    }

    /**
     * Update sub-activity status using Action and DTO.
     */
    public function updatedSubActivityStatuses($value, $subActivityId, UpdateSubActivityStatusAction $action, ActivityQueries $queries)
    {
        $subActivity = Activity::findOrFail($subActivityId);
        
        // Authorization check
        Gate::authorize('update', $subActivity);

        $dto = SubActivityDTO::fromArray([
            'id' => $subActivityId,
            'status' => $value
        ]);

        try {
            $action->execute($dto);
            $this->notifyToast('success', 'Statut de la sous-activité mis à jour.');
            $this->loadActivity($queries);
        } catch (\Exception $e) {
            $this->notifyToast('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer la ressource.
     */
    public function deleteResource(string $resourceId, ActivityQueries $queries)
    {
        $resource = Resource::findOrFail($resourceId);
        
        Gate::authorize('delete', $resource);

        try {
            $resource->delete();
            $this->notifyToast('success', 'Ressource supprimée avec succès.');
            $this->loadActivity($queries);
        } catch (\Exception $e) {
            $this->notifyToast('error', 'Erreur lors de la suppression.');
        }
    }

    public function render()
    {
        return view('livewire.activity.management');
    }
}