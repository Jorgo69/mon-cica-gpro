<?php

namespace App\Livewire\VBeta\Audit;

use Livewire\Attributes\Lazy;
use Livewire\Component;

use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Lazy]
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

        // Si ce n'est pas un Super Admin (Root), on filtre par organisation
        // On considère Root un IT_ADMIN sans organization_id
        $isRoot = $user->hasRole('IT_ADMIN') && is_null($user->organization_id);

        if (!$isRoot) {
            $query->where('organization_id', $user->organization_id);
        }

        if ($this->subjectId && $this->subjectType) {
            $query->where('subject_id', $this->subjectId)
                  ->where('subject_type', $this->subjectType);
        }

        return view('livewire.v-beta.audit.activity-history-livewire', [
            'activities' => $query->paginate($this->perPage)
        ]);
    }
}
