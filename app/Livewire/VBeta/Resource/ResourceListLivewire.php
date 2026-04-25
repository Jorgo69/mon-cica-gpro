<?php

namespace App\Livewire\VBeta\Resource;

use App\Models\Resource;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Lazy]
class ResourceListLivewire extends Component
{
    
    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render()
    {
        $user = Auth::user();

        $resources = Resource::query();

        return view('livewire.v-beta.resource.resource-list-livewire', [
            'resources' => $resources->paginate(10),
        ]);
    }
}
