<?php

namespace App\Livewire\V1\Audit;

use App\Enums\AccountType;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityHistoryLivewire extends Component
{
    use WithPagination;

    public $subjectId = null;
    public $subjectType = null;
    public $perPage = 10;

    public function mount($subject = null)
    {
        if ($subject) {
            $this->subjectId = $subject->id;
            $this->subjectType = get_class($subject);
        }
    }

    
    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render()
    {
        $user = auth()->user();
        $query = Activity::query()
            ->latest();

        // ROOT voit tout, INDEPENDENT voit ses propres logs, les autres voient leur org
        if ($user->role === AccountType::ROOT) {
            // pas de filtre
        } elseif ($user->role === AccountType::INDEPENDENT) {
            $query->where('causer_id', $user->id);
        } else {
            $query->where('properties->organization_id', $user->organization_id);
        }

        if ($this->subjectId && $this->subjectType) {
            $query->where('subject_id', $this->subjectId)
                  ->where('subject_type', $this->subjectType);
        }

        return view('livewire.v1.audit.activity-history-livewire', [
            'activities' => $query->paginate($this->perPage)
        ]);
    }
}
