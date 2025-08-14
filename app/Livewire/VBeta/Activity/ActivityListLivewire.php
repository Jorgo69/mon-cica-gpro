<?php

namespace App\Livewire\VBeta\Activity;

use App\Models\User;
use Livewire\Component;
use App\Models\Activity;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ActivityListLivewire extends Component
{
    use WithPagination;

   public $search = '';
    public $statusFilter = '';
    public $responsibleUserFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'statusFilter', 'responsibleUserFilter', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $user = Auth::user();
        $activities = Activity::with('responsibleUser', 'result.specificObjective.logicalFramework.project')
                              ->orderBy($this->sortField, $this->sortDirection);

        // Filtre de permission: Afficher les activités où l'utilisateur est le responsable
        $activities->where('responsible_user_id', $user->id);

        // Appliquer la recherche textuelle sur le projet lié
        if ($this->search) {
            $activities->whereHas('result.specificObjective.logicalFramework.project', function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                      ->orWhere('short_title', 'like', '%' . $this->search . '%')
                      ->orWhere('project_code', 'like', '%' . $this->search . '%');
            });
        }

        // Appliquer le filtre de statut (champ sur la table 'activities')
        if ($this->statusFilter) {
            $activities->where('status', $this->statusFilter);
        }

        // Appliquer le filtre par responsable (champ sur la table 'activities')
        if ($this->responsibleUserFilter) {
            $activities->where('responsible_user_id', $this->responsibleUserFilter);
        }

        // Obtenir les options pour les filtres
        $availableUsers = User::orderBy('name')->get();
        $activityStatuses = Activity::select('status')->distinct()->pluck('status');

        return view('livewire.v-beta.activity.activity-list-livewire', [
            'activities' => $activities->paginate(10),
            'availableUsers' => $availableUsers,
            'activityStatuses' => $activityStatuses,
        ]);
    }
}
