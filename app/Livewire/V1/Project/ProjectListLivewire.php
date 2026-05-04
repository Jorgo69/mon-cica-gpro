<?php

namespace App\Livewire\V1\Project;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\User;
use App\Services\Queries\UserQueryService;
use Illuminate\Support\Facades\Auth;

class ProjectListLivewire extends Component
{
    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $statusFilter = '';
    public $responsibleUserFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public string $projectId = '' ;

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

    public function deleteProject($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('delete', $project);

        $project->delete();

        session()->flash('success', __('Le projet a été supprimé avec succès.'));
    }

    
    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render()
    {
        $user = Auth::user();

        $projects = Project::query()
            ->visibleTo($user);

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

        // Eager load relations to avoid N+1
        $projects->with(['creator:id,name', 'projectType:id,name']);

        // Appliquer le tri
        $projects->orderBy($this->sortField, $this->sortDirection);

        // Obtenir les options pour les filtres (par exemple, tous les utilisateurs disponibles)
        $availableUsers = UserQueryService::forCurrentOrg()->orderBy('name')->get();

        // Obtenir les statuts de projet uniques (si vous voulez un filtre dynamique)
        $projectStatuses = Project::select('status')->distinct()->get()->pluck('status');


        return view('livewire.project.list', [
            'projects' => $projects->paginate(10),
            'availableUsers' => $availableUsers,
            'projectStatuses' => $projectStatuses,
        ]);
    }
}