<?php

namespace App\Livewire\VBeta\Admin\Category;

use App\Actions\Admin\Category\SaveCategoryAction;
use App\Models\Category;
use Livewire\Component;

class CategoryModalFormLivewire extends Component
{
    public ?string $editingCategoryId = null;
    public $editing = false;
    public $name = '';
    public $description = '';

    protected $rules = [
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
    ];

    /**
     * Se monte au chargement du composant.
     */
    public function mount(?string $editingCategoryId = null)
    {
        $this->editingCategoryId = $editingCategoryId;

        if ($this->editingCategoryId) {
            $this->editing = true;
            $category = Category::findOrFail($this->editingCategoryId);
            $this->name = $category->name;
            $this->description = $category->description;
        }
    }

    /**
     * Sauvegarde ou met à jour la catégorie via l'Action CQRS.
     */
    public function saveCategory(SaveCategoryAction $action)
    {
        $this->validate();

        try {
            $action->execute(
                data: ['name' => $this->name, 'description' => $this->description],
                categoryId: $this->editingCategoryId,
            );

            $this->dispatch('categorySaved');
            $this->reset(['name', 'description', 'editingCategoryId', 'editing']);
        } catch (\Exception $e) {
            session()->flash('error', "Erreur : " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.category.modal-form');
    }
}
