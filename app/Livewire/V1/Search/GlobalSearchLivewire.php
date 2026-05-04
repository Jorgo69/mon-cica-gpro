<?php

namespace App\Livewire\V1\Search;

use App\Services\Search\GlobalSearchService;
use App\Services\UserMeta;
use Livewire\Component;

class GlobalSearchLivewire extends Component
{
    #[\Livewire\Attributes\Validate('string|max:100')]
    public string $search = '';
    public $results = [];
    public $quickActions = [];
    public $recentSearches = [];
    public $isVisible = false;

    public function mount()
    {
        $this->recentSearches = UserMeta::get('recent_searches', []);
    }

    public function updatedSearch()
    {
        if (!is_string($this->search)) {
            $this->search = '';
            return;
        }
        $service = app(GlobalSearchService::class);
        $this->results = $service->search($this->search, 4)->toArray();
    }

    public function toggle()
    {
        $this->isVisible = !$this->isVisible;
        if ($this->isVisible) {
            $service = app(GlobalSearchService::class);
            $this->quickActions = $service->quickActions();
            $this->recentSearches = UserMeta::get('recent_searches', []);
        } else {
            $this->saveRecentSearch();
            $this->reset(['search', 'results']);
        }
    }

    public function setSearchFromRecent(int $index)
    {
        $recent = UserMeta::get('recent_searches', []);
        if (isset($recent[$index])) {
            $this->search = $recent[$index];
            $this->updatedSearch();
        }
    }

    public function selectResult(string $url)
    {
        // Securite : valider que l'URL est interne
        if (!str_starts_with($url, url('/'))) {
            return;
        }
        $this->saveRecentSearch();
        $this->isVisible = false;
        $this->reset(['search', 'results']);
        return $this->redirect($url, navigate: false);
    }

    public function clearRecentSearches()
    {
        UserMeta::set('recent_searches', []);
        $this->recentSearches = [];
    }

    private function saveRecentSearch(): void
    {
        if (empty($this->search) || strlen($this->search) < 2) return;

        $recent = UserMeta::get('recent_searches', []);
        // Eviter les doublons, max 5
        $recent = array_filter($recent, fn($r) => $r !== $this->search);
        array_unshift($recent, $this->search);
        $recent = array_slice($recent, 0, 5);
        UserMeta::set('recent_searches', $recent);
    }

    public function render()
    {
        return view('livewire.search.global-search');
    }
}
