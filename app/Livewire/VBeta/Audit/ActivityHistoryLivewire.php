<?php

namespace App\Livewire\VBeta\Audit;

use Livewire\Component;
use Livewire\WithPagination;
use App\Enums\AccountType;
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

    public function render()
    {
        $user = auth()->user();
        $query = Activity::query()->latest();

        // system_admin voit tout, les autres filtrés par org active
        if ($user->account_type !== AccountType::SYSTEM_ADMIN) {
            $orgId = session('current_organization_id');
            if ($orgId) {
                $query->where('organization_id', $orgId);
            }
        }

        if ($this->subjectId && $this->subjectType) {
            $query->where('subject_id', $this->subjectId)
                  ->where('subject_type', $this->subjectType);
        }

        return view('livewire.audit.activity-history', [
            'activities' => $query->paginate($this->perPage)
        ]);
    }
}
