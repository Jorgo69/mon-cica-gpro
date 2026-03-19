<?php

namespace App\Livewire\VBeta\Admin\Project;

use App\Models\User;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\GeneralAdministration;

class ProjectListLivewire extends Component
{

    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $responsibleUserFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $projectStatuses = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'responsibleUserFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }
    
    

    public function updatedProjectStatuses($value, $projectId)
    {
        $project = Project::find($projectId);

        if ($project) {
            $project->update(['status' => $value]);
            // Optionnel : émettre un événement pour prévenir que le statut a changé
            $this->dispatch('projectStatusUpdated', projectId: $projectId, status: $value);
        }
    }

    public function render()
    {
        $user = Auth::user();

        $projects = Project::query();

        // Filtrer par projets créés par l'utilisateur ou où l'utilisateur est responsable d'activités
        // Si admin, il voit tout (enlevé la restriction du Dashboard pour la liste globale si c'est la vue Admin)
        // Mais ici c'est ProjectListLivewire dans Admin, donc on garde la logique de visibilité demandée ou on l'élargit
        $isAdmin = in_array($user->role, [\App\Enums\AccountType::SYSTEM_ADMIN, \App\Enums\AccountType::ORG_ADMIN]);

        if (!$isAdmin) {
            $projects->where(function ($query) use ($user) {
                $query->where('creator_user_id', $user->id)
                      ->orWhereHas('logicalFramework.specificObjectives.results.activities', function ($subQuery) use ($user) {
                          $subQuery->where('responsible_user_id', $user->id);
                      });
            });
        }

        // Appliquer la recherche textuelle
        if ($this->search) {
            $projects->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('short_title', 'like', '%' . $this->search . '%')
                      ->orWhere('project_code', 'like', '%' . $this->search . '%');
            });
        }

        // Appliquer le filtre de statut
        if ($this->statusFilter) {
            $projects->where('status', $this->statusFilter);
        }

        // Appliquer le filtre par responsable (le créateur du projet)
        if ($this->responsibleUserFilter) {
            $projects->where('creator_user_id', $this->responsibleUserFilter);
        }

        // Appliquer le tri
        $projects->orderBy($this->sortField, $this->sortDirection);

        // Obtenir les options pour les filtres
        $availableUsers = User::orderBy('name')->get();
        
        $projectStatuses = \App\Enums\ProjectStatus::cases();
        
        return view('livewire.v-beta.admin.project.project-list-livewire', [
            'projects' => $projects->paginate(12),
            'availableUsers' => $availableUsers,
            'projectStatuses' => $projectStatuses,
        ]);
    }
}
