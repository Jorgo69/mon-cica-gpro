<?php

namespace App\Livewire\VBeta\Admin\Category;

use App\Services\Admin\CategoryQueryService;
use Livewire\WithPagination;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\Attributes\On;

#[Lazy]
class CategoryManagementLivewire extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    public $showModal = false;
    public ?string $editingCategoryId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    /**
     * Ouvre la modale via un événement Alpine dispatché au navigateur.
     */
    public function openModal(?string $categoryId = null)
    {
        $this->editingCategoryId = $categoryId;
        $this->showModal = true;
        $this->dispatch('open-modal-category-management');
    }

    /**
     * Ferme la modale.
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->editingCategoryId = null;
        $this->dispatch('close-modal-category-management');
    }

    /**
     * Écouteur pour l'événement "categorySaved".
     */
    #[On('categorySaved')]
    public function refreshCategories()
    {
        $this->closeModal();
        $this->resetPage();
    }

    public function render(CategoryQueryService $queryService)
    {
        return view('livewire.v-beta.admin.category.category-management-livewire', [
            'categories' => $queryService->list(
                search: $this->search,
                sortField: $this->sortField,
                sortDirection: $this->sortDirection,
            ),
        ]);
    }
}
