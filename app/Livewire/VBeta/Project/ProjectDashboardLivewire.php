<?php

namespace App\Livewire\VBeta\Project;

use App\Models\Project;
use App\Models\Activity;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectDashboardLivewire extends Component
{
    use WithPagination;

    public $project;
    public string $projectId;
    public $search = '';
    public $statusFilter = '';
    public $responsibleUserFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'responsibleUserFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function mount(string $projectId)
    {
        $this->projectId = $projectId;
        // On charge uniquement le projet pour l'affichage de son titre, sans toutes les activités.
        $this->project = Project::withCount(['qualitativeEvaluations', 'budgets'])->findOrFail($this->projectId);
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

    /**
     * Renders the view for the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Démarrer la requête depuis le modèle Activity
        $activitiesQuery = Activity::query();
        
        // Joindre les tables intermédiaires pour filtrer par l'ID du projet
        $activitiesQuery->whereHas('result.specificObjective.logicalFramework', function ($query) {
            $query->where('project_id', $this->projectId);
        });

        // Appliquer la recherche textuelle
        if ($this->search) {
            $activitiesQuery->where('description', 'like', '%' . $this->search . '%');
        }

        // Appliquer le filtre de statut
        if ($this->statusFilter) {
            $activitiesQuery->where('status', $this->statusFilter);
        }

        // Appliquer le filtre par responsable
        if ($this->responsibleUserFilter) {
            $activitiesQuery->where('responsible_user_id', $this->responsibleUserFilter);
        }

        // Obtenir la liste de tous les responsables pour les options du filtre
        $availableUsers = User::whereHas('responsibleActivities', function ($query) {
            $query->whereHas('result.specificObjective.logicalFramework', function ($subQuery) {
                $subQuery->where('project_id', $this->projectId);
            });
        })->orderBy('name')->get();
        
        // Compter toutes les activités (sans pagination) pour les indicateurs
        $allActivities = (clone $activitiesQuery)->get();
        
        return view('livewire.v-beta.project.project-dashboard-livewire', [
            'activities' => $activitiesQuery->with('responsibleUser')->paginate($this->perPage),
            'allActivities' => $allActivities,
            'availableUsers' => $availableUsers,
        ]);
    }
}
