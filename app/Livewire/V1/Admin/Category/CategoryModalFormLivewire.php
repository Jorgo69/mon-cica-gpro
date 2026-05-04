<?php

namespace App\Livewire\V1\Admin\Category;

use App\Actions\Admin\Category\SaveCategoryAction;
use App\Enums\AdminCategoryType;
use App\Models\GeneralAdministration;
use Livewire\Component;

class CategoryModalFormLivewire extends Component
{
    public ?string $editingCategoryId = null;
    public $editing = false;
    public $name = '';
    public $description = '';
    public $type = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => ['required', \Illuminate\Validation\Rule::in(array_column(AdminCategoryType::cases(), 'value'))],
        ];
    }

    protected $validationAttributes = [
        'name' => 'nom',
        'type' => 'type de categorie',
    ];

    public function mount(?string $editingCategoryId = null)
    {
        $this->editingCategoryId = $editingCategoryId;

        if ($this->editingCategoryId) {
            $this->editing = true;
            $category = GeneralAdministration::findOrFail($this->editingCategoryId);

            if ($category->is_system) {
                abort(403, 'Les categories systeme ne peuvent pas etre modifiees.');
            }

            $this->name = $category->name;
            $this->description = $category->description;
            $this->type = $category->type instanceof AdminCategoryType ? $category->type->value : $category->type;
        }
    }

    public function saveCategory(SaveCategoryAction $action)
    {
        $this->validate();

        try {
            $action->execute(
                data: [
                    'name' => $this->name,
                    'description' => $this->description,
                    'type' => $this->type,
                ],
                categoryId: $this->editingCategoryId,
            );

            $this->dispatch('categorySaved');
            $this->reset(['name', 'description', 'type', 'editingCategoryId', 'editing']);
        } catch (\Exception $e) {
            session()->flash('error', "Erreur : " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.category.category-modal-form-livewire', [
            'categoryTypes' => AdminCategoryType::cases(),
        ]);
    }
}
