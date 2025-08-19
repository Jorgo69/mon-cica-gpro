<?php

namespace App\Livewire\VBeta\Resource;

use Livewire\Component;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ResourceFormLivewire extends Component
{
    public $activityId;
    public $resourceToEditId = null; // Reçoit l'ID de la ressource à éditer
    public $users;

    public $editing = false;
    public $resourcesData = []; // Le tableau de données pour le/les formulaire(s)

    public bool $bulkEditOpen = false;
    public array $allResources = [];


    protected function rules()
    {
        return [
            'resourcesData.*.name' => 'required|string|max:255',
            'resourcesData.*.type' => ['required', 'string', Rule::in(['Humain', 'Materiel', 'Financier'])],
            'resourcesData.*.quantity' => 'required|integer|min:1',
            'resourcesData.*.unit_cost' => 'nullable|numeric|min:0',
            'resourcesData.*.total_cost' => 'nullable|numeric|min:0',
            'resourcesData.*.category' => 'nullable|string|max:100',
            'resourcesData.*.responsible_user_id' => 'nullable|uuid|exists:users,id',
        ];
    }

    public function mount(string $activityId, ?string $resourceToEditId = null)
    {
        $this->activityId = $activityId;
        $this->users = User::orderBy('name')->get();
        $this->resourceToEditId = $resourceToEditId;

        if ($this->resourceToEditId) {
            $this->editing = true;
            $this->loadResourceForEdit($this->resourceToEditId);
        } else {
            $this->editing = false;
            $this->addBlankResource();
        }
    }

    public function loadResourceForEdit(string $resourceId)
    {
        $resource = Resource::findOrFail($resourceId);
        $this->resourcesData = [
            0 => [
                'id' => $resource->id,
                'name' => $resource->name,
                'type' => $resource->type,
                'quantity' => $resource->quantity,
                'unit_cost' => $resource->unit_cost,
                'total_cost' => $resource->total_cost,
                'category' => $resource->category,
                'responsible_user_id' => $resource->responsible_user_id,
            ]
        ];
    }
    
    public function addBlankResource()
    {
        $this->resourcesData[] = [
            'name' => '', 'type' => '', 'quantity' => 1, 'unit_cost' => 0.00,
            'total_cost' => 0.00, 'category' => '', 'responsible_user_id' => null
        ];
    }

    public function removeResource($index)
    {
        unset($this->resourcesData[$index]);
        $this->resourcesData = array_values($this->resourcesData);
        if (empty($this->resourcesData)) {
            $this->addBlankResource();
        }
    }
    
    public function updated($propertyName)
    {
        $parts = explode('.', $propertyName);
        if (count($parts) === 3 && ($parts[2] === 'quantity' || $parts[2] === 'unit_cost')) {
            $index = $parts[1];
            $this->resourcesData[$index]['total_cost'] = $this->resourcesData[$index]['quantity'] * $this->resourcesData[$index]['unit_cost'];
        }
    }

    
    
    public function saveResources()
    {
        $this->validate();
    
        try {
            DB::beginTransaction();
    
            foreach ($this->resourcesData as $data) {
                // Détecte s'il s'agit d'une mise à jour ou d'une création
                Resource::updateOrCreate(
                    ['id' => $data['id'] ?? null],
                    array_merge($data, ['activity_id' => $this->activityId])
                );
            }
            DB::commit();
    
            $this->dispatch('resourceSaved');
    
            // Réinitialiser le formulaire après la sauvegarde
            $this->resetForm();
    
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', "Une erreur est survenue lors de la sauvegarde : " . $e->getMessage());
        }
    }

    

   

    public function resetForm()
    {
        $this->reset(['resourcesData', 'editing', 'resourceToEditId']);
        $this->addBlankResource();
    }
    
    public function render()
    {
        return view('livewire.v-beta.resource.resource-form-livewire');
    }
}