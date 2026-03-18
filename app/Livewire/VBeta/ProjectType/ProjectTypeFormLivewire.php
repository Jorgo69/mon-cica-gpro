<?php

namespace App\Livewire\VBeta\ProjectType;

use App\Models\DynamicProjectField;
use App\Models\GeneralAdministration;
use App\Models\ProjectType;
use Illuminate\Support\Str;
use Livewire\Component;

class ProjectTypeFormLivewire extends Component
{
    public $projectTypeId;
    public $name = '';
    public $description = '';
    public $category = '';
    public $fields = [];
    public $projectCategories = [];

    // Méthode de montage, appelée à l'initialisation du composant.
    public function mount($projectTypeId = null)
    {
        if ($projectTypeId) {
            $this->projectTypeId = $projectTypeId;
            $projectType = ProjectType::with('dynamicFields')->findOrFail($projectTypeId);
            $this->name = $projectType->name;
            $this->description = $projectType->description;
            $this->category = $projectType->category;
            $this->fields = $projectType->dynamicFields->map(function ($field) {
                // S'il s'agit d'une liste déroulante, on décode les options JSON en tableau.
                if ($field->input_type === 'select') {
                    $field->options = json_decode($field->options, true) ?? [];
                }
                return $field->toArray();
            })->toArray();
        } else {
            $this->addField();
        }

        $this->projectCategories = GeneralAdministration::where('type', 'project_type_category')->get();
    }

    // Ajoute un nouveau champ dynamique au formulaire
    public function addField()
    {
        $this->fields[] = [
            'field_name' => '',
            'question_text' => '',
            'input_type' => 'text',
            'render_as' => null, // Nouvelle propriété pour le rendu
            'options' => [],
            'order' => count($this->fields) + 1,
            'target_project_field' => '',
            'section' => '',            
            'is_required' => false,
            // Les délimiteurs seront générés automatiquement
            'delimiter_start' => null,
            'delimiter_end' => null,
        ];
    }

    // Retire un champ dynamique du formulaire
    public function removeField($index)
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }
    
    // Ajoute une option à un champ de type select
    public function addOption($fieldIndex)
    {
        $this->fields[$fieldIndex]['options'][] = [
            'label' => '',
            'value' => '',
        ];
    }

    // Retire une option d'un champ de type select
    public function removeOption($fieldIndex, $optionIndex)
    {
        unset($this->fields[$fieldIndex]['options'][$optionIndex]);
        $this->fields[$fieldIndex]['options'] = array_values($this->fields[$fieldIndex]['options']);
    }

    // Sauvegarde ou met à jour le type de projet et ses champs
    public function save()
    {
        $validationRules = [
            'name' => 'required|string|max:100|unique:project_types,name,' . $this->projectTypeId,
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'fields.*.question_text' => 'required|string|max:255',
            'fields.*.field_name' => 'required|string|max:100',
            'fields.*.input_type' => 'required|string|in:text,textarea,select,date,number',
            'fields.*.order' => 'required|integer',
            'fields.*.target_project_field' => 'nullable|string|max:100',
            'fields.*.section' => 'nullable|string|max:100',
            'fields.*.is_required' => 'boolean',
        ];

        foreach ($this->fields as $index => $field) {
            if ($field['input_type'] === 'select') {
                $validationRules['fields.' . $index . '.render_as'] = 'required|string|in:select,radio,checkbox';
                $validationRules['fields.' . $index . '.options'] = 'array|min:1';
                $validationRules['fields.' . $index . '.options.*.label'] = 'required|string|max:255';
                $validationRules['fields.' . $index . '.options.*.value'] = 'required|string|max:255';
            }
        }
    
        $this->validate($validationRules);

        try {
            if ($this->projectTypeId) {
                $projectType = ProjectType::findOrFail($this->projectTypeId);
                $projectType->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'category' => $this->category,
                ]);
            } else {
                $projectType = ProjectType::create([
                    'id' => (string) Str::uuid(),
                    'name' => $this->name,
                    'description' => $this->description,
                    'category' => $this->category,
                ]);
                $this->projectTypeId = $projectType->id;
            }

            if ($this->projectTypeId) {
                $existingFieldNames = collect($this->fields)->pluck('field_name')->toArray();
                DynamicProjectField::where('project_type_id', $this->projectTypeId)
                    ->whereNotIn('field_name', $existingFieldNames)
                    ->delete();
            }

            foreach ($this->fields as $index => $fieldData) {
                // Si c'est un nouveau champ, on génère les délimiteurs
                if (empty($fieldData['delimiter_start'])) {
                    $uniqueId = (string) Str::uuid();
                    $fieldData['delimiter_start'] = '{{--START:' . $uniqueId . '--}}';
                    $fieldData['delimiter_end'] = '{{--END:' . $uniqueId . '--}}';
                }

                $fieldData['project_type_id'] = $this->projectTypeId;
                $fieldData['order'] = $index + 1;

                $fieldData['section'] = trim($fieldData['section'] ?? '') ?: 'general';
                
                // Gérer la sérialisation des options en JSON si c'est un 'select'
                if ($fieldData['input_type'] === 'select') {
                    $fieldData['options'] = json_encode($fieldData['options']);
                } else {
                    $fieldData['options'] = null;
                    $fieldData['render_as'] = null;
                }

                DynamicProjectField::updateOrCreate(
                    [
                        'project_type_id' => $this->projectTypeId,
                        'field_name' => $fieldData['field_name']
                    ],
                    $fieldData
                );
                
            }

            session()->flash('message', 'Type de projet sauvegardé avec succès !');
            return redirect()->route('admin.type.of.project');

        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.v-beta.project-type.project-type-form-livewire');
    }
}
