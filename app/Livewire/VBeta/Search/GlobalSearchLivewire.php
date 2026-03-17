<?php

namespace App\Livewire\VBeta\Search;

use Livewire\Component;

use App\Services\Search\GlobalSearchService;

class GlobalSearchLivewire extends Component
{
    public $search = '';
    public $results = [];
    public $isVisible = false;

    public function updatedSearch()
    {
        $service = app(GlobalSearchService::class);
        $this->results = $service->search($this->search, 5)->toArray();
    }

    public function toggle()
    {
        $this->isVisible = !$this->isVisible;
        if (!$this->isVisible) {
            $this->reset(['search', 'results']);
        }
    }

    public function render()
    {
        return view('livewire.v-beta.search.global-search-livewire');
    }
}
