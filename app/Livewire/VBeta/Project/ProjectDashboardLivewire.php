<?php

namespace App\Livewire\VBeta\Project;

use App\Models\Project;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectDashboardLivewire extends Component
{
    use WithPagination, AuthorizesRequests;

    public $project;
    public string $projectId;
    public $search = '';
    public $statusFilter = '';
    public $responsibleUserFilter = '';
    public $perPage = 10;
    
    // Propriété pour l'ID de l'activité sélectionnée pour la modale
    public $selectedActivityId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'responsibleUserFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    // Listeners pour les événements venant des autres composants
    protected $listeners = [
        'closeActivityDetails' => 'closeActivityDetails',
    ];

    public function mount(string $projectId)
    {
        $this->projectId = $projectId;
        $this->project = Project::withCount(['qualitativeEvaluations', 'budgets'])
                        ->findOrFail($this->projectId);

        abort_if(
            !auth()->user()->can('view', $this->project),
            403,
            __('messages.access_denied')
        );
    }
    
    // Méthodes pour réinitialiser la pagination lors du changement de filtre.
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function updatingResponsibleUserFilter()
    {
        $this->resetPage();
    }
    
    public function updatingPerPage()
    {
        $this->resetPage();
    }
    
    // Méthode pour ouvrir la modale des détails d'activité
    public function openActivityDetails($activityId)
    {
        $this->selectedActivityId = $activityId;
    }
    
    // Méthode pour fermer la modale des détails d'activité
    public function closeActivityDetails()
    {
        $this->selectedActivityId = null;
    }

    /**
     * Renders the view for the component.
     *
     * @return \Illuminate\View\View
     */
    
    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render()
    {
        $activitiesQuery = Activity::query();
        
        $activitiesQuery->whereHas('result.specificObjective.logicalFramework', function ($query) {
            $query->where('project_id', $this->projectId);
        });

        if ($this->search) {
            $activitiesQuery->where('description', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $activitiesQuery->where('status', $this->statusFilter);
        }

        if ($this->responsibleUserFilter) {
            $activitiesQuery->where('responsible_user_id', $this->responsibleUserFilter);
        }

        $availableUsers = User::whereHas('responsibleActivities', function ($query) {
            $query->whereHas('result.specificObjective.logicalFramework', function ($subQuery) {
                $subQuery->where('project_id', $this->projectId);
            });
        })->orderBy('name')->get();
        
        $allActivities = (clone $activitiesQuery)->get();
        
        return view('livewire.v-beta.project.project-dashboard-livewire', [
            'activities' => $activitiesQuery->with('responsibleUser')->paginate($this->perPage),
            'allActivities' => $allActivities,
            'availableUsers' => $availableUsers,
        ]);
    }
}
