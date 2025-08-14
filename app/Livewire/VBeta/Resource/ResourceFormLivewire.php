<?php

namespace App\Livewire\VBeta\Resource;

use Livewire\Component;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ResourceFormLivewire extends Component
{
    public $activityId;
    public $resources; // Liste des ressources déjà existantes
    public $newResources = []; // Tableau pour les nouveaux formulaires
    public $users;

    protected $listeners = ['resourceAdded' => 'refreshResources'];

    public function mount(string $activityId)
    {
        $this->activityId = $activityId;
        $this->users = User::orderBy('name')->get();
        
        // Charger les ressources existantes
        $this->resources = Resource::with('responsibleUser')->where('activity_id', $this->activityId)->get();

        // Ajouter un premier formulaire vide par défaut
        $this->addBlankResource();
    }

    protected function rules()
    {
        return [
            'newResources.*.name' => 'required|string|max:255',
            'newResources.*.type' => ['required', 'string', Rule::in(['Humain', 'Materiel', 'Financier'])],
            'newResources.*.quantity' => 'required|integer|min:1',
            'newResources.*.unit_cost' => 'nullable|numeric|min:0',
            'newResources.*.total_cost' => 'nullable|numeric|min:0',
            'newResources.*.category' => 'nullable|string|max:100',
            'newResources.*.responsible_user_id' => 'nullable|uuid|exists:users,id',
        ];
    }
    
    // Ajoute un formulaire vide au tableau
    public function addBlankResource()
    {
        $this->newResources[] = [
            'name' => '',
            'type' => '',
            'quantity' => 1,
            'unit_cost' => 0.00,
            'total_cost' => 0.00,
            'category' => '',
            'responsible_user_id' => null,
        ];
    }

    // Retire un formulaire du tableau
    public function removeResource($index)
    {
        unset($this->newResources[$index]);
        $this->newResources = array_values($this->newResources); // Réindexer le tableau
    }

    // Calcule le coût total pour un formulaire spécifique
    public function updated($propertyName)
    {
        $parts = explode('.', $propertyName);
        if (count($parts) === 3 && ($parts[2] === 'quantity' || $parts[2] === 'unit_cost')) {
            $index = $parts[1];
            $this->newResources[$index]['total_cost'] = $this->newResources[$index]['quantity'] * $this->newResources[$index]['unit_cost'];
        }
    }

    public function saveResources()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            foreach ($this->newResources as $data) {
                // Création de la ressource
                Resource::create([
                    'activity_id' => $this->activityId,
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'quantity' => $data['quantity'],
                    'unit_cost' => $data['unit_cost'],
                    'total_cost' => $data['total_cost'],
                    'category' => $data['category'],
                    'responsible_user_id' => $data['responsible_user_id'],
                ]);
            }
            
            DB::commit();

            session()->flash('success', 'Ressources sauvegardées avec succès !');

            // Mettre à jour la liste des ressources existantes
            $this->resources = Resource::with('responsibleUser')->where('activity_id', $this->activityId)->get();
            $this->newResources = [];
            $this->addBlankResource();

            // ...
    session()->flash('success', 'Ressources sauvegardées avec succès !');

    // Émettre l'événement pour la mise à jour
    $this->dispatch('resourceSaved');

    // Réinitialiser les champs du formulaire après la sauvegarde
    $this->newResources = [];
    $this->addBlankResource();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', "Une erreur est survenue lors de la sauvegarde : " . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.v-beta.resource.resource-form-livewire');
    }
}