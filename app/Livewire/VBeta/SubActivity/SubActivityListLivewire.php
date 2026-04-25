<?php

namespace App\Livewire\VBeta\SubActivity;

use App\Models\Activity;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class SubActivityListLivewire extends Component
{
    
    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render()
    {
        $subActivities = Activity::whereNotNull('parent_id')->get();

        return view('livewire.v-beta.sub-activity.sub-activity-list-livewire', [
            'subActivities' => $subActivities,
        ]);
    }
}

