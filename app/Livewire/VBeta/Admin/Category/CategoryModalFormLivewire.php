<?php

namespace App\Livewire\VBeta\Admin\Category;

use Illuminate\Support\Facades\DB;
use App\Models\GeneralAdministration;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class CategoryModalFormLivewire extends Component
{
    public ?string $editingCategoryId = null;
    public $editing = false;
    public $name, $description;

    // Règles de validation
    protected $rules = [
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
    ];

    /**
     * Se monte au chargement du composant.
     * @param string|null $editingCategoryId L'ID de la catégorie à éditer.
     */
    public function mount( ?string $editingCategoryId = null)
    {
        $this->editingCategoryId = $editingCategoryId;
        
        if ($this->editingCategoryId) {
            $this->editing = true;
            $this->loadCategoryForEdit($this->editingCategoryId);
        } else {
            $this->editing = false;
        }
    }

    /**
     * Charge les données de la catégorie pour l'édition.
     */
    public function loadCategoryForEdit(string $categoryId)
    {
        $category = GeneralAdministration::findOrFail($categoryId);
        $this->name = $category->name;
        $this->description = $category->description;
    }

    /**
     * Sauvegarde ou met à jour la catégorie.
     */
    public function saveCategory()
    {
        $this->validate();
    
        try {
            DB::beginTransaction();
            
            $data = [
                'name'        => $this->name,
                'description' => $this->description,
                'type'        => 'project_type_category',
            ];
            
            GeneralAdministration::updateOrCreate(
                ['id' => $this->editingCategoryId],
                $data
            );
            
            DB::commit();
    
            // Émet un événement pour que le parent se rafraîchisse
            $this->dispatch('categorySaved');
    
            // Réinitialise le formulaire
            $this->resetForm();
    
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', "Une erreur est survenue lors de la sauvegarde : " . $e->getMessage());
        }
    }
    
    /**
     * Réinitialise les propriétés du formulaire.
     */
    public function resetForm()
    {
        $this->reset(['name', 'description', 'editingCategoryId', 'editing']);
    }

    public function render()
    {
        return view('livewire.v-beta.admin.category.category-modal-form-livewire');
    }
}
