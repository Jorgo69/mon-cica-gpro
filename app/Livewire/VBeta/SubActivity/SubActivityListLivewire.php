<?php

namespace App\Livewire\VBeta\SubActivity;

use App\Models\SubActivity;
use Livewire\Component;

class SubActivityListLivewire extends Component
{
    public function render()
    {
        $subActivities = SubActivity::query();

        return view('livewire.v-beta.sub-activity.sub-activity-list-livewire', [
            'subActivities' => $subActivities,
        ]);
    }
}
