<?php

namespace App\Livewire\V1\SubActivity;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\Queries\UserQueryService;
use App\Models\Activity;
use App\Livewire\Traits\WithToastNotifications;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SubActivityFormLivewire extends Component
{
    use AuthorizesRequests, WithToastNotifications;
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
        $this->users = UserQueryService::forCurrentOrg()->orderBy('name')->get();
        $this->subActivityToEditId = $subActivityToEditId;

        // Charger les dates de l'activité parente
        $activity = Activity::findOrFail($activityId);
        $this->activity = $activity;
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
        $subActivity = Activity::findOrFail($subActivityId);
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

    public function toggleMilestone($index)
    {
        $this->subActivitiesData[$index]['is_milestone'] = 
        $this->subActivitiesData[$index]['is_milestone'] ? 0 : 1;
    }
    
    public function addBlankSubActivity()
    {
            $this->subActivitiesData[] = [
            'description' => '',
            'is_milestone' => 0,
            'start_date' => '',
            'end_date' => '',
            'responsible_user_id' => '',
        ];

        $this->dispatch('subActivityAdded'); // Animation scroll
    }

    public function removeSubActivity($index)
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
        $this->validate();

        try {
            DB::beginTransaction();

            foreach ($this->subActivitiesData as $data) {
                $subActivityData = [
                    'description'         => $data['description'],
                    'is_milestone'        => $data['is_milestone'] == "1",
                    'start_date'          => $data['start_date'],
                    'end_date'            => $data['end_date'],
                    'responsible_user_id' => $data['responsible_user_id'] ?: null,
                    'parent_id'           => $this->activityId,
                    'project_id'          => $this->activity->project_id,
                    'result_id'           => $this->activity->result_id,
                    'status'              => \App\Enums\ActivityStatus::ONGOING->value,
                ];

                if (!empty($data['id'])) {
                    // update
                    Activity::where('id', $data['id'])->update($subActivityData);
                    \Log::info('Updated subActivity', ['id' => $data['id']]);
                } else {
                    // create
                    $subActivityData['creator_user_id'] = auth()->id();
                    $created = Activity::create($subActivityData);
                    \Log::info('Created subActivity', ['id' => $created->id]);
                }
            }
            DB::commit();

            $this->dispatch('subActivitySaved');
            $this->resetForm();

            $this->notifyToast('success', 'Sous-activité ajoutée avec succès');

        } catch (\Exception $e) {
            \Log::error('Erreur saveSubActivities', ['error' => $e->getMessage()]);
            $this->notifyToast('error', "Erreur lors de la sauvegarde : " . $e->getMessage());
        }
    }

    

    

    public function resetForm()
    {
        $this->reset(['subActivitiesData', 'editing', 'subActivityToEditId']);
        $this->addBlankSubActivity();
    }


    public function render()
    {
        return view('livewire.sub-activity.form');
    }
}
