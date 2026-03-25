<?php

namespace App\Livewire\VBeta\Activity;

use App\Models\User;
use Livewire\Component;
use App\Models\Activity;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Queries\ActivityQueries;

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

    public function render(ActivityQueries $activityQueries)
    {
        $user = Auth::user();

        $filters = [
            'search' => $this->search,
            'statusFilter' => $this->statusFilter,
            'responsibleUserFilter' => $this->responsibleUserFilter,
        ];

        try {
            $activities = $activityQueries->getPaginatedActivitiesForUser(
                $user,
                $filters,
                $this->sortField,
                $this->sortDirection,
                10
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[ActivityList] Error fetching activities: ' . $e->getMessage());
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Erreur lors du chargement des activités.']);
            $activities = collect(); // Avoid crashing the view
        }

        // Obtenir les options pour les filtres
        $availableUsers = User::orderBy('name')->get();
        // Optionnel: utiliser des enums si dispo, sinon la requête de statuts existants
        $activityStatuses = Activity::select('status')->whereNotNull('status')->distinct()->pluck('status');

        return view('livewire.activity.list', [
            'activities' => $activities,
            'availableUsers' => $availableUsers,
            'activityStatuses' => $activityStatuses,
        ]);
    }
}
