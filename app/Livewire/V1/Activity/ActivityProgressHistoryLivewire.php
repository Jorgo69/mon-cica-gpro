<?php

namespace App\Livewire\V1\Activity;

use App\Models\Activity;
use App\Models\ProgressTracker;
use Livewire\Component;

class ActivityProgressHistoryLivewire extends Component
{
    public $activityId;
    public $history = [];

    public function mount(string $activityId)
    {
        $this->activityId = $activityId;
        $this->loadHistory();
    }

    public function loadHistory()
    {
        $this->history = ProgressTracker::where('activity_id', $this->activityId)
            ->with('creator')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->take(20)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.v1.activity.activity-progress-history-livewire');
    }
}
