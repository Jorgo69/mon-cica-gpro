<?php

namespace App\Livewire\VBeta\Project;

use App\Models\Project;
use Livewire\Component;

class ProjectProgressComparisonLivewire extends Component
{
    public $projectId;
    public $activities = [];
    public $projectProgress = 0;
    public $projectPlanned = 0;

    public function mount(string $projectId)
    {
        $this->projectId = $projectId;
        $this->loadData();
    }

    public function loadData()
    {
        $project = Project::with([
            'logicalFramework.specificObjectives.results.activities.responsibleUser',
        ])->findOrFail($this->projectId);

        $allActivities = $project->getAllActivities();
        $totalReal = 0;
        $totalPlanned = 0;

        $this->activities = $allActivities->map(function ($activity) use (&$totalReal, &$totalPlanned) {
            $real = $activity->calculateProgress();
            $planned = $activity->getPlannedProgressPercentage();
            $totalReal += $real;
            $totalPlanned += $planned;
            $gap = $real - $planned;

            return [
                'id' => $activity->id,
                'description' => $activity->description,
                'responsible' => $activity->responsibleUser->name ?? 'N/A',
                'status' => $activity->status,
                'real' => round($real),
                'planned' => round($planned),
                'gap' => round($gap),
                'start_date' => $activity->start_date,
                'end_date' => $activity->end_date,
            ];
        })->toArray();

        $count = count($this->activities);
        $this->projectProgress = $count > 0 ? round($totalReal / $count) : 0;
        $this->projectPlanned = $count > 0 ? round($totalPlanned / $count) : 0;
    }

    public function render()
    {
        return view('livewire.v-beta.project.project-progress-comparison-livewire');
    }
}
