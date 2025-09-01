<?php

namespace App\Livewire\VBeta\SubActivity;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Log;
use Illuminate\Support\Str;
use App\Models\User;
use Livewire\Component;
use App\Models\SubActivity;
use Illuminate\Support\Facades\DB;

class SubActivityFormLivewire extends Component
{
    use AuthorizesRequests;
    public $activityId, $activityStartDate, $activityEndDate;
    public $subActivityToEditId = null; // Reçoit l'ID de la ressource à éditer
    public $users;

    public $editing = false;
    public $subActivitiesData = []; // Le tableau de données pour le/les formulaire(s)

    public bool $bulkEditOpen = false;
    public array $allSubActivities = [];

    public $activity;


    protected function rules()
    {
        return [
        'subActivitiesData.*.description' => 'required|string|max:255',
        'subActivitiesData.*.is_milestone' => 'nullable|in:0,1',
        'subActivitiesData.*.start_date' => 'required|date',
        'subActivitiesData.*.end_date' => 'required|date|after_or_equal:subActivitiesData.*.start_date',
        'subActivitiesData.*.responsible_user_id' => 'nullable|exists:users,id',
        ];
        [
        'subActivitiesData.*.description.required' => 'La description est requise.',
        'subActivitiesData.*.start_date.required' => 'La date de début est requise.',
        'subActivitiesData.*.end_date.required' => 'La date de fin est requise.',
        'subActivitiesData.*.end_date.after_or_equal' => 'La date de fin ne peut pas être avant la date de début.',
        'subActivitiesData.*.responsible_user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
        ];
    }


    public function mount(string $activityId, ?string $subActivityToEditId = null)
    {
        $this->activityId = $activityId;
        $this->users = User::orderBy('name')->get();
        $this->subActivityToEditId = $subActivityToEditId;

        // Charger les dates de l'activité parente
        $activity = \App\Models\Activity::findOrFail($activityId);
        $this->activityStartDate = $activity->start_date;
        $this->activityEndDate = $activity->end_date;

        if ($this->subActivityToEditId) {
            $this->editing = true;
            $this->loadSubActivityForEdit($this->subActivityToEditId);
        } else {
            $this->editing = false;
            $this->addBlankSubActivity();
        }
    }

    public function loadSubActivityForEdit(string $subActivityId)
    {
        $subActivity = SubActivity::findOrFail($subActivityId);
        $this->subActivitiesData = [
            0 => [
                'id' => $subActivity->id,
                'description' => $subActivity->description,
                'is_milestone' => $subActivity->is_milestone,
                'start_date' => $subActivity->start_date,
                'end_date' => $subActivity->end_date,
                'responsible_user_id' => $subActivity->responsible_user_id,
            ]
        ];
    }
    
    public function addBlankSubActivity()
    {
        $this->subActivitiesData[] = [
            'description' => '', 'is_milestone' => '', 'start_date' => '',
            'end_date' => '', 'responsible_user_id' => null
        ];
    }

    public function removeResource($index)
    {
        unset($this->subActivitiesData[$index]);
        $this->subActivitiesData = array_values($this->subActivitiesData);
        if (empty($this->subActivitiesData)) {
            $this->addBlankSubActivity();
        }
    }
    

    public function updated($propertyName)
    {
        $parts = explode('.', $propertyName);

        // On s'intéresse aux champs de type subActivitiesData.XXX.start_date ou end_date
        if (str_starts_with($propertyName, 'subActivitiesData.') && 
            (str_ends_with($propertyName, '.start_date') || str_ends_with($propertyName, '.end_date'))
        ) {
            $index = $parts[1];

            $startDate = $this->subActivitiesData[$index]['start_date'] ?? null;
            $endDate = $this->subActivitiesData[$index]['end_date'] ?? null;

            // Si une des dates est manquante, on ne valide pas encore
            if (!$startDate || !$endDate) {
                return;
            }

            $start = \Carbon\Carbon::parse($startDate);
            $end = \Carbon\Carbon::parse($endDate);
            $parentStart = \Carbon\Carbon::parse($this->activityStartDate);
            $parentEnd = \Carbon\Carbon::parse($this->activityEndDate);

            // Réinitialiser les erreurs liées aux dates
            $this->resetErrorBag([
                "subActivitiesData.{$index}.start_date",
                "subActivitiesData.{$index}.end_date"
            ]);

            // 1. start_date ne doit pas être avant l'activité parente
            if ($start->lt($parentStart)) {
                $this->addError("subActivitiesData.{$index}.start_date", 'La date de début ne peut pas être avant celle de l\'activité parente.');
                $this->subActivitiesData[$index]['start_date'] = $parentStart->format('Y-m-d');
            }

            // 2. end_date ne doit pas être après l'activité parente
            if ($end->gt($parentEnd)) {
                $this->addError("subActivitiesData.{$index}.end_date", 'La date de fin ne peut pas être après celle de l\'activité parente.');
                $this->subActivitiesData[$index]['end_date'] = $parentEnd->format('Y-m-d');
            }

            // 3. start_date ne doit pas être après end_date
            if ($start->gt($end)) {
                $this->addError("subActivitiesData.{$index}.end_date", 'La date de fin doit être postérieure à la date de début.');
                $this->subActivitiesData[$index]['end_date'] = $start->format('Y-m-d');
            }
        }
    }

   
    public function saveSubActivities()
    {
        try {
            foreach ($this->subActivitiesData as $data) {
                $subActivityData = [
                    'description'         => $data['description'],
                    'is_milestone'        => $data['is_milestone'] == "1",
                    'start_date'          => $data['start_date'],
                    'end_date'            => $data['end_date'],
                    'responsible_user_id' => $data['responsible_user_id'],
                    'activity_id'         => $this->activityId,
                    'status'              => 'En Cours', // 👈 valeur par défaut
                ];

                if (!empty($data['id'])) {
                    // update
                    SubActivity::where('id', $data['id'])->update($subActivityData);
                    \Log::info('Updated subActivity', ['id' => $data['id']]);
                } else {
                    // create
                    $created = SubActivity::create($subActivityData);
                    \Log::info('Created subActivity', ['id' => $created->id]);
                }
            }

            $this->dispatch('subActivitySaved');
            $this->resetForm();

        } catch (\Exception $e) {
            \Log::error('Erreur saveSubActivities', ['error' => $e->getMessage()]);
            session()->flash('error', "Erreur lors de la sauvegarde : " . $e->getMessage());
        }
    }

    

    

    public function resetForm()
    {
        $this->reset(['subActivitiesData', 'editing', 'subActivityToEditId']);
        $this->addBlankSubActivity();
    }


    public function render()
    {
        return view('livewire.v-beta.sub-activity.sub-activity-form-livewire');
    }
}
