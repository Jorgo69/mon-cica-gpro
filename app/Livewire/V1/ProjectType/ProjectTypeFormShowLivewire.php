<?php

namespace App\Livewire\V1\ProjectType;

use Livewire\Component;
use App\Models\ProjectType;

class ProjectTypeFormShowLivewire extends Component
{
     public $projectTypeId;
    public $projectType;

    public function mount($projectTypeId)
    {
        $this->projectTypeId = $projectTypeId;
        $this->projectType = ProjectType::with('dynamicFields')->findOrFail($projectTypeId);
    }
    public function render()
    {
        return view('livewire.project-type.form-show');
    }
}
