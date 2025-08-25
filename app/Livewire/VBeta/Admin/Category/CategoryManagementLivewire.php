<?php

namespace App\Livewire\VBeta\Admin\Category;

use App\Models\GeneralAdministration;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On;

class CategoryManagementLivewire extends Component
{
     use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $responsibleUserFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $projectStatuses = [];
    
    public $showModal = false;
    public ?string $editingCategoryId = null; // ID de la category en cours d'édition
    

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'responsibleUserFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    // Réinitialiser la pagination lors d'une mise à jour de la recherche
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingResponsibleUserFilter()
    {
        $this->resetPage();
    }

    // Gérer le tri
    public function sortBy($field)
    {
        if ($this->sortField === 'id') {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    /**
     * Ouvre la modale pour la création ou l'édition.
     * @param string|null $categoryId L'ID de la catégorie à éditer, si applicable.
     */
    public function openModal(?string $categoryId = null)
    {
        $this->editingCategoryId = $categoryId;
        $this->showModal = true;
    }

    // Ferme la modale
    public function closeModal()
    {
        $this->showModal = false;
        $this->editingCategoryId = null;
    }

    /**
     * Écouteur pour l'événement "categorySaved".
     * Ferme la modale et rafraîchit la liste.
     */
    #[On('categorySaved')]
    public function refreshCategories()
    {
        $this->closeModal();
        $this->resetPage();
    }

    // Affiche la vue
    public function render()
    {
        $categories = GeneralAdministration::query();

        // Filtre de recherche
        if (!empty($this->search)) {
            $categories->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        // Tri
        $categories->orderBy($this->sortField, $this->sortDirection);

        $categories->where('type', 'project_type');

        return view('livewire.v-beta.admin.category.category-management-livewire', [
            'categories' => $categories->paginate(10),
        ]);
    }
}
