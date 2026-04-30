<?php

namespace App\Livewire\VBeta\SubActivity;

use App\Models\Activity;
use Livewire\Component;

class SubActivityListLivewire extends Component
{
    
    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render()
    {
        $subActivities = Activity::whereNotNull('parent_id')->get();

        return view('livewire.sub-activity.list', [
            'subActivities' => $subActivities,
        ]);
    }
}

