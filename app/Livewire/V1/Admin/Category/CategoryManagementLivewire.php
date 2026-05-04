<?php

namespace App\Livewire\V1\Admin\Category;

use App\Enums\AdminCategoryType;
use App\Services\Admin\CategoryQueryService;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Traits\WithToastNotifications;

class CategoryManagementLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public $search = '';
    public $typeFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $showModal = false;
    public ?string $editingCategoryId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
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

    public function openModal(?string $categoryId = null)
    {
        $this->editingCategoryId = $categoryId;
        $this->showModal = true;
        $this->dispatch('open-modal-category-management');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingCategoryId = null;
        $this->dispatch('close-modal-category-management');
    }

    public function deleteCategory(string $id)
    {
        $category = \App\Models\GeneralAdministration::find($id);
        if (!$category) return;

        if ($category->is_system) {
            $this->notifyToast('error', 'Les categories systeme ne peuvent pas etre supprimees.');
            return;
        }

        $category->delete();
        $this->notifyToast('success', 'Categorie supprimee.');
    }

    #[On('categorySaved')]
    public function refreshCategories()
    {
        $this->closeModal();
        $this->resetPage();
    }

    public function render(CategoryQueryService $queryService)
    {
        $type = $this->typeFilter ? AdminCategoryType::tryFrom($this->typeFilter) : null;

        return view('livewire.v1.admin.category.category-management-livewire', [
            'categories' => $queryService->list(
                type: $type,
                search: $this->search,
                sortField: $this->sortField,
                sortDirection: $this->sortDirection,
            ),
            'categoryTypes' => AdminCategoryType::cases(),
        ]);
    }
}
