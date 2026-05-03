<?php

namespace App\Livewire\VBeta\ProposalProject;

use App\Actions\CreateProjectAction;
use App\DTOs\ProjectDTO;
use App\Models\Activity;
use App\Models\Budget;
use App\Models\DynamicProjectField;
use App\Models\LogicalFramework;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectType;
use App\Models\Result;
use App\Models\SpecificObjective;
use App\Models\User;
use App\Services\Queries\UserQueryService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Traits\WithToastNotifications;

class ProposalProjectFormLivewire extends Component
{
    use WithFileUploads, AuthorizesRequests;
    use WithToastNotifications;

    // =========================================================================
    // PROPERTIES: Wizard / Stepper
    // =========================================================================
    public $currentStep = 1;
    public $totalSteps = 6;
    public $stepDetails = [];
    public bool $isSubmitting = false;

    // =========================================================================
    // PROPERTIES: Main Project Data (projects table)
    // =========================================================================
    public $projectId; // Null in creation, UUID in edition
    public $projectCode;
    public $projectTitle;
    public $projectShortTitle = '';
    public $projectStartDate;
    public $projectEndDate;
    public $projectStatus = \App\Enums\ProjectStatus::DRAFT->value;

    // =========================================================================
    // PROPERTIES: Specific Step Data / Context
    // =========================================================================
    public $contextDescription;
    public $problemAnalysis;
    public $strategy;
    public $justification;

    // =========================================================================
    // PROPERTIES: Dynamic Fields
    // =========================================================================
    public $selectedProjectTypeId;
    public $dynamicFormFields = [];  // Definitions grouped by section
    public $dynamicFieldValues = []; // User-entered values for dynamic fields

    // =========================================================================
    // PROPERTIES: Collections and Related Data
    // =========================================================================
    public $allProjectTypes = [];    // Used for the project type dropdown
    public $users = [];              // Used for responsible person dropdowns
    public $uploadedDocuments = [];  // New files being uploaded
    public $existingDocuments = [];  // Files already in DB (as array for Livewire hydration)

    // =========================================================================
    // PROPERTIES: Logical Framework Arrays
    // =========================================================================
    public $initialLogicalFramework = [
        'general_objective' => '',
        'general_obj_indicators' => '',
        'general_obj_verification_sources' => '',
        'assumptions' => '',
        'indicators_list' => [],
    ];
    public $specificObjectives = [];
    public $expectedResults = [];
    public $activities = [];
    public $budgets = [];

    // =========================================================================
    // PROPERTIES: Status & UI
    // =========================================================================
    public $statusMessage = '';
    public $statusType = 'hidden';

    // =========================================================================
    // LISTENERS & CONFIGURATION
    // =========================================================================
    protected $listeners = ['stepChanged'];

    /**
     * Validation Rules
     */
    protected function rules()
    {
        $rules = [
            'projectCode' => ['required', 'string', 'max:255', Rule::unique('projects', 'project_code')->ignore($this->projectId)],
            'projectTitle' => 'required|string|max:255',
            'projectStartDate' => 'required|date',
            'projectEndDate' => 'required|date|after_or_equal:projectStartDate',
            'selectedProjectTypeId' => 'nullable|uuid|exists:project_types,id',
            'contextDescription' => 'nullable|string',
            'problemAnalysis' => 'nullable|string',
            'strategy' => 'nullable|string',
            'justification' => 'nullable|string',
            'uploadedDocuments.*' => 'nullable|file|max:50000|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,csv,txt,zip',

            // Logical Framework Basic
            'initialLogicalFramework.general_objective' => 'required|string',
            'initialLogicalFramework.general_obj_indicators' => 'nullable|string',
            'initialLogicalFramework.general_obj_verification_sources' => 'nullable|string',
            'initialLogicalFramework.assumptions' => 'nullable|string',

            // Specific Objectives
            'specificObjectives.*.description' => 'required|string',
            'specificObjectives.*.verification_sources' => 'nullable|string',
            'specificObjectives.*.assumptions' => 'nullable|string',

            // Results and Activities
            'expectedResults.*.description' => 'required|string',
            'activities.*.description' => 'required|string',
            'activities.*.responsible_user_id' => [
                'required', 'uuid',
                Rule::exists('users', 'id')->where('organization_id', \App\Services\OrgContext::orgId()),
            ],
            'activities.*.start_date' => [
                'required',
                'date',
                'after_or_equal:projectStartDate',
                'before_or_equal:projectEndDate'
            ],
            'activities.*.end_date' => [
                'required',
                'date',
                'before_or_equal:projectEndDate'
            ],
            'activities.*.status' => ['nullable', Rule::in(array_column(\App\Enums\ActivityStatus::cases(), 'value'))],

            // Budgets
            'budgets.*.description' => 'nullable|string|max:255',
            'budgets.*.total_cost' => 'nullable|numeric|min:0',
        ];

        // Add dynamic rules based on current step
        $currentSection = $this->getSectionFromStep($this->currentStep);
        if ($currentSection && isset($this->dynamicFormFields[$currentSection])) {
            foreach ($this->dynamicFormFields[$currentSection] as $field) {
                $key = 'dynamicFieldValues.' . $field['field_name'];
                $validationRule = $field['is_required'] ? 'required' : 'nullable';

                if ($field['input_type'] === 'number') {
                    $validationRule .= '|numeric';
                } elseif ($field['input_type'] === 'date') {
                    $validationRule .= '|date';
                } elseif ($field['input_type'] === 'select' && ($field['render_as'] ?? '') === 'checkbox') {
                    $validationRule .= '|array';
                } else {
                    $validationRule .= '|string';
                }

                $rules[$key] = $validationRule;
            }
        }

        return $rules;
    }

    /**
     * User-friendly validation attributes
     */
    protected function validationAttributes()
    {
        $attributes = [
            'projectCode' => 'code du projet',
            'projectTitle' => 'titre du projet',
            'projectStartDate' => 'date de début',
            'projectEndDate' => 'date de fin',
            'selectedProjectTypeId' => 'type de projet',
            'contextDescription' => 'description du contexte',
            'initialLogicalFramework.general_objective' => 'objectif général',
            'initialLogicalFramework.general_obj_indicators' => 'indicateurs de l\'objectif général',
            'initialLogicalFramework.general_obj_verification_sources' => 'sources de vérification de l\'objectif général',
            'initialLogicalFramework.assumptions' => 'hypothèses de l\'objectif général',
            'specificObjectives.*.description' => 'description de l\'objectif spécifique',
            'expectedResults.*.description' => 'description du résultat attendu',
            'activities.*.description' => 'description de l\'activité',
            'budgets.*.description' => 'description de la ligne budgétaire',
        ];

        // Dynamic indexed attributes for clear error messages
        foreach ($this->specificObjectives as $i => $obj) {
            $n = $i + 1;
            $attributes["specificObjectives.{$i}.description"] = "description de l'objectif spécifique n°{$n}";
            $attributes["specificObjectives.{$i}.verification_sources"] = "sources de vérification de l'objectif n°{$n}";
            $attributes["specificObjectives.{$i}.assumptions"] = "hypothèses de l'objectif n°{$n}";
        }
        foreach ($this->expectedResults as $i => $res) {
            $attributes["expectedResults.{$i}.description"] = "description du résultat attendu n°".($i + 1);
        }
        foreach ($this->activities as $i => $act) {
            $n = $i + 1;
            $attributes["activities.{$i}.description"] = "description de l'activité n°{$n}";
            $attributes["activities.{$i}.responsible_user_id"] = "responsable de l'activité n°{$n}";
            $attributes["activities.{$i}.start_date"] = "date de début de l'activité n°{$n}";
            $attributes["activities.{$i}.end_date"] = "date de fin de l'activité n°{$n}";
            $attributes["activities.{$i}.status"] = "statut de l'activité n°{$n}";
        }

        foreach ($this->dynamicFormFields as $section => $fields) {
            foreach ($fields as $field) {
                $attributes['dynamicFieldValues.' . $field['field_name']] = strtolower($field['question_text']);
            }
        }

        return $attributes;
    }

    // =========================================================================
    // LIFECYCLE
    // =========================================================================

    public function mount($projectId = null)
    {
        if ($projectId) {
            $project = Project::findOrFail($projectId);
            $this->authorize('update', $project);
        } else {
            $this->authorize('create', Project::class);
        }

        $this->users = UserQueryService::forCurrentOrg()->get();
        $this->allProjectTypes = ProjectType::visibleForOrg(\App\Services\OrgContext::orgId())->get();
        $this->initStepDetails();

        if ($projectId) {
            $this->loadExistingProject($projectId);
        } else {
            $this->initNewProject();
        }

        // Initialize dynamic fields if a type is already selected
        if ($this->selectedProjectTypeId) {
            $this->loadDynamicFields();
            $this->initializeDynamicFieldValues();
        }
    }

    private function initStepDetails()
    {
        $this->stepDetails = [
            ['title' => __('project.step_1.title'), 'description' => __('project.step_1.description')],
            ['title' => __('project.step_2.title'), 'description' => __('project.step_2.description')],
            ['title' => __('project.step_3.title'), 'description' => __('project.step_3.description')],
            ['title' => __('project.step_4.title'), 'description' => __('project.step_4.description')],
            ['title' => __('project.step_5.title'), 'description' => __('project.step_5.description')],
            ['title' => __('project.step_6.title'), 'description' => __('project.step_6.description')],
        ];
    }

    private function loadExistingProject($projectId)
    {
        $project = \App\Services\Queries\LogframeQueryService::forProject($projectId)->project();

        if (!$project) {
            $this->initNewProject();
            return;
        }

        $this->authorize('update', $project);

        $this->projectId = $project->id;
        $this->projectCode = $project->project_code;
        $this->projectTitle = $project->title;
        $this->projectShortTitle = $project->short_title;
        $this->projectStatus = $project->status;
        $this->selectedProjectTypeId = $project->project_type_id;

        $this->contextDescription = $project->description;
        $this->problemAnalysis = $project->problem_analysis;
        $this->strategy = $project->strategy;
        $this->justification = $project->justification;

        $this->projectStartDate = $project->start_date ? Carbon::parse($project->start_date)->format('Y-m-d') : null;
        $this->projectEndDate = $project->end_date ? Carbon::parse($project->end_date)->format('Y-m-d') : null;

        // Documents as array for hydration safety
        $this->existingDocuments = $project->projectDocuments->toArray();

        // Dynamic Field Values
        $dynamicFieldsArray = $project->general_objectives;
        if (is_array($dynamicFieldsArray)) {
            $this->dynamicFieldValues = $dynamicFieldsArray;
        }

        // Logical Framework
        if ($project->logicalFramework) {
            $lf = $project->logicalFramework;
            $lfData = collect($lf->toArray())->only([
                'id', 'project_id', 'general_objective', 'general_obj_indicators',
                'general_obj_verification_sources', 'assumptions',
                'organization_id', 'creator_user_id',
            ])->toArray();
            $this->initialLogicalFramework = array_merge($lfData, [
                'indicators_list' => $lf->indicatorItems->map(fn ($i) => $i->only(['id', 'description', 'verification_source', 'assumption']))->toArray(),
            ]);
            $this->specificObjectives = ($lf->specificObjectives ?? collect())->map(function ($obj) {
                $data = collect($obj->toArray())->only([
                    'id', 'logical_framework_id', 'description', 'indicators',
                    'verification_sources', 'assumptions', 'organization_id', 'creator_user_id',
                ])->toArray();
                $data['indicators_list'] = $obj->indicatorItems->map(fn ($i) => $i->only(['id', 'description', 'verification_source', 'assumption']))->toArray();
                return $data;
            })->toArray();

            $this->expectedResults = [];
            $this->activities = [];

            foreach ($lf->specificObjectives ?? [] as $obj) {
                foreach ($obj->results ?? [] as $res) {
                    $resData = collect($res->toArray())->only([
                        'id', 'specific_objective_id', 'description', 'indicators',
                        'verification_sources', 'assumptions', 'organization_id', 'creator_user_id',
                    ])->toArray();
                    $this->expectedResults[] = $resData;
                    foreach ($res->activities ?? [] as $act) {
                        $activity = collect($act->toArray())->only([
                            'id', 'result_id', 'project_id', 'description', 'responsible_user_id',
                            'start_date', 'end_date', 'status', 'budget', 'is_milestone',
                            'organization_id', 'creator_user_id',
                        ])->toArray();
                        if (isset($activity['start_date'])) $activity['start_date'] = Carbon::parse($activity['start_date'])->format('Y-m-d');
                        if (isset($activity['end_date'])) $activity['end_date'] = Carbon::parse($activity['end_date'])->format('Y-m-d');
                        if (isset($activity['status']) && $activity['status'] instanceof \App\Enums\ActivityStatus) {
                            $activity['status'] = $activity['status']->value;
                        }
                        $this->activities[] = $activity;
                    }
                }
            }
        } else {
            $this->addSpecificObjective();
            $this->addExpectedResult();
        }

        $this->budgets = $project->budgets->toArray();
    }

    private function initNewProject()
    {
        $this->projectId = null;
        $this->projectStartDate = null;
        $this->projectEndDate = null;
        
        $this->addSpecificObjective();
        $this->addExpectedResult();
        $this->addActivity();
        $this->addBudget();
    }

    // =========================================================================
    // WIZARD NAVIGATION
    // =========================================================================

    public function nextStep()
    {
        try {
            $this->validateCurrentStep();
            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;
            }
            $this->dispatch('stepChanged');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = collect($e->errors())->flatten()->take(3)->implode(' | ');
            $this->notifyToast('warning', $errorMessages, 'Corrigez avant de continuer');
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error in nextStep: ' . $e->getMessage());
            $this->notifyToast('error', 'Une erreur inattendue est survenue.');
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
        $this->dispatch('stepChanged');
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps && $step <= $this->currentStep + 1) {
            $this->currentStep = $step;
            $this->dispatch('stepChanged');
        }
    }

    public function validateCurrentStep()
    {
        $rules = $this->getRulesForStep($this->currentStep);
        
        if (!empty($rules)) {
            $this->validate($rules);
        }

        // Dynamic validation
        $sectionName = $this->getSectionFromStep($this->currentStep);
        if ($sectionName && isset($this->dynamicFormFields[$sectionName])) {
            $dynamicRules = [];
            foreach ($this->dynamicFormFields[$sectionName] as $field) {
                $key = 'dynamicFieldValues.' . $field['field_name'];
                $dynamicRules[$key] = $field['is_required'] ? 'required' : 'nullable';
                if ($field['input_type'] === 'number') $dynamicRules[$key] .= '|numeric';
                elseif ($field['input_type'] === 'date') $dynamicRules[$key] .= '|date';
            }
            $this->validate($dynamicRules);
        }
    }

    protected function getRulesForStep($step)
    {
        $allRules = $this->rules();
        $stepRules = [];

        switch ($step) {
            case 1:
                $stepRules = Arr::only($allRules, [
                    'selectedProjectTypeId', 'projectCode', 'projectTitle', 
                    'projectStartDate', 'projectEndDate'
                ]);
                break;
            case 2:
                $stepRules = Arr::only($allRules, ['contextDescription']);
                if (!empty($this->uploadedDocuments)) {
                    $stepRules['uploadedDocuments.*'] = $allRules['uploadedDocuments.*'];
                }
                break;
            case 3:
                $stepRules = Arr::only($allRules, ['initialLogicalFramework.general_objective']);
                foreach ($this->specificObjectives as $index => $obj) {
                    $stepRules["specificObjectives.{$index}.description"] = $allRules['specificObjectives.*.description'];
                }
                break;
            case 4:
                foreach ($this->expectedResults as $index => $res) {
                    $stepRules["expectedResults.{$index}.description"] = $allRules['expectedResults.*.description'];
                }
                break;
            case 5:
                foreach ($this->activities as $index => $act) {
                    $stepRules["activities.{$index}.description"] = $allRules['activities.*.description'];
                    $stepRules["activities.{$index}.responsible_user_id"] = $allRules['activities.*.responsible_user_id'];
                    $stepRules["activities.{$index}.start_date"] = $allRules['activities.*.start_date'];
                    $stepRules["activities.{$index}.end_date"] = [
                        'required',
                        'date',
                        "after_or_equal:activities.{$index}.start_date",
                        'before_or_equal:projectEndDate',
                    ];
                }
                break;
            case 6:
                foreach ($this->budgets as $index => $budget) {
                    $stepRules["budgets.{$index}.description"] = $allRules['budgets.*.description'];
                }
                break;
        }

        return $stepRules;
    }

    private function getSectionFromStep(int $step): ?string
    {
        $map = [
            1 => 'informations_cles',
            2 => 'contexte_documents',
            3 => 'cadre_logique',
            4 => 'resultats_attendus',
            5 => 'activites_initiales',
            6 => 'finalisation',
        ];
        return $map[$step] ?? null;
    }

    // =========================================================================
    // DYNAMIC FIELDS HANDLING
    // =========================================================================

    public function updatedSelectedProjectTypeId($value)
    {
        $this->loadDynamicFields();
        $this->dynamicFieldValues = [];
        $this->initializeDynamicFieldValues();
    }

    public function loadDynamicFields()
    {
        if (!$this->selectedProjectTypeId) {
            $this->dynamicFormFields = [];
            return;
        }

        $this->dynamicFormFields = DynamicProjectField::where('project_type_id', $this->selectedProjectTypeId)
            ->orderBy('order')
            ->get()
            ->map(function($field) {
                $arr = $field->toArray();
                // options est deja decode par le cast JSON du model
                // s'assurer que c'est bien un array
                if ($arr['input_type'] === 'select' && is_string($arr['options'] ?? null)) {
                    $arr['options'] = json_decode($arr['options'], true) ?? [];
                }
                $arr['is_required'] = (bool) $arr['is_required'];
                // input_type enum -> string value
                $arr['input_type'] = $field->input_type?->value ?? $arr['input_type'];
                return $arr;
            })
            ->groupBy('section')
            ->filter(fn($fields, $section) => !empty($section))
            ->toArray();
    }

    private function initializeDynamicFieldValues()
    {
        foreach ($this->dynamicFormFields as $section => $fields) {
            foreach ($fields as $fieldDef) {
                if ($fieldDef['input_type'] === 'select' && ($fieldDef['render_as'] ?? '') === 'checkbox') {
                    if (!isset($this->dynamicFieldValues[$fieldDef['field_name']])) {
                        $this->dynamicFieldValues[$fieldDef['field_name']] = [];
                    }
                }
            }
        }
    }

    // =========================================================================
    // DATA MANIPULATION (Objectives, Results, Activities, Budgets)
    // =========================================================================

    public function addSpecificObjective()
    {
        $this->specificObjectives[] = [
            'id' => Str::orderedUuid()->toString(),
            'description' => '',
            'indicators' => '',
            'verification_sources' => '',
            'assumptions' => '',
            'indicators_list' => [],
        ];
    }

    public function removeSpecificObjective($index)
    {
        unset($this->specificObjectives[$index]);
        $this->specificObjectives = array_values($this->specificObjectives);
    }

    public function addExpectedResult()
    {
        $this->expectedResults[] = ['id' => Str::orderedUuid()->toString(), 'description' => ''];
    }

    public function removeExpectedResult($index)
    {
        unset($this->expectedResults[$index]);
        $this->expectedResults = array_values($this->expectedResults);
    }

    public function addActivity()
    {
        $this->activities[] = [
            'id' => null, 'description' => '', 'responsible_user_id' => '', 'start_date' => '', 'end_date' => '',
            'status' => \App\Enums\ActivityStatus::DRAFT->value, 'justification' => '', 'is_milestone' => false, 'budget' => 0
        ];
    }

    public function removeActivity($index)
    {
        unset($this->activities[$index]);
        $this->activities = array_values($this->activities);
    }

    public function addIndicator(string $level, ?int $parentIndex = null)
    {
        $indicator = [
            'id' => null,
            'description' => '',
            'verification_source' => '',
            'assumption' => '',
        ];

        if ($level === 'logframe') {
            $this->initialLogicalFramework['indicators_list'][] = $indicator;
        } elseif ($level === 'objective' && $parentIndex !== null) {
            $this->specificObjectives[$parentIndex]['indicators_list'][] = $indicator;
        }
    }

    public function removeIndicator(string $level, ?int $parentIndex, int $indicatorIndex)
    {
        if ($level === 'logframe') {
            unset($this->initialLogicalFramework['indicators_list'][$indicatorIndex]);
            $this->initialLogicalFramework['indicators_list'] = array_values($this->initialLogicalFramework['indicators_list']);
        } elseif ($level === 'objective' && $parentIndex !== null) {
            unset($this->specificObjectives[$parentIndex]['indicators_list'][$indicatorIndex]);
            $this->specificObjectives[$parentIndex]['indicators_list'] = array_values($this->specificObjectives[$parentIndex]['indicators_list']);
        }
    }

    public function addBudget()
    {
        $this->budgets[] = [
            'id' => Str::orderedUuid()->toString(), 'description' => '', 'quantity' => null, 'unit_cost' => null,
            'total_cost' => null, 'category' => '', 'responsible_user_id' => null
        ];
    }

    public function removeBudget($index)
    {
        unset($this->budgets[$index]);
        $this->budgets = array_values($this->budgets);
    }

    // =========================================================================
    // DOCUMENT HANDLING
    // =========================================================================

    public function confirmDeleteDocument($documentId)
    {
        $this->dispatch('confirm-delete-document', ['documentId' => $documentId]);
    }

    public function removeUploadedFile($index)
    {
        unset($this->uploadedDocuments[$index]);
        $this->uploadedDocuments = array_values($this->uploadedDocuments);
    }

    public function removeExistingDocument($documentId)
    {
        try {
            DB::beginTransaction();
            $document = ProjectDocument::findOrFail($documentId);
            Storage::disk('public')->delete($document->file_path);
            $document->delete();

            $this->existingDocuments = collect($this->existingDocuments)
                ->reject(fn($doc) => (is_array($doc) ? $doc['id'] : $doc->id) == $documentId)
                ->values()
                ->toArray();

            DB::commit();
            $this->notifyToast('success', 'Document supprimé.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error removing document $documentId: " . $e->getMessage());
            $this->notifyToast('error', 'Erreur lors de la suppression.');
        }
    }

    // =========================================================================
    // AI ASSISTANT (per-field)
    // =========================================================================

    public ?string $aiActiveField = null;
    public ?string $aiSuggestion = null;
    public ?array $aiLogframe = null;
    public bool $aiLoading = false;

    protected function getAiContext(): string
    {
        $parts = [];
        if ($this->projectTitle) $parts[] = "Titre: {$this->projectTitle}";
        if ($this->contextDescription) $parts[] = "Contexte: " . strip_tags($this->contextDescription);
        if ($this->problemAnalysis) $parts[] = "Probleme: " . strip_tags($this->problemAnalysis);
        if ($this->strategy) $parts[] = "Strategie: " . strip_tags($this->strategy);
        if ($this->justification) $parts[] = "Justification: " . strip_tags($this->justification);
        if ($this->projectStartDate) $parts[] = "Date debut: {$this->projectStartDate}";
        if ($this->projectEndDate) $parts[] = "Date fin: {$this->projectEndDate}";
        if (!empty($this->initialLogicalFramework['general_objective'])) {
            $parts[] = "Objectif general: {$this->initialLogicalFramework['general_objective']}";
        }
        return implode("\n", $parts);
    }

    protected function getFieldPrompt(string $field): array
    {
        $locale = app()->getLocale();
        $lang = $locale === 'fr' ? 'français' : 'English';
        $base = "Tu es un expert en cadre logique et gestion de projets pour ONG. Reponds en {$lang}.";

        $prompts = [
            'contextDescription' => [
                'instruction' => "{$base} Genere une description de contexte professionnelle (3-5 phrases). Decris le contexte, les enjeux et les besoins identifies.",
                'label' => 'contexte du projet',
            ],
            'problemAnalysis' => [
                'instruction' => "{$base} Redige une analyse du probleme (3-5 phrases). Identifie les causes racines, les consequences et l'urgence d'agir.",
                'label' => 'analyse du probleme',
            ],
            'strategy' => [
                'instruction' => "{$base} Propose une strategie d'intervention (3-4 phrases). Decris l'approche, les methodes et les partenariats envisages.",
                'label' => 'strategie',
            ],
            'justification' => [
                'instruction' => "{$base} Redige une justification du projet (3-4 phrases). Explique pourquoi ce projet est necessaire et pertinent.",
                'label' => 'justification',
            ],
            'general_objective' => [
                'instruction' => "{$base} Formule UN objectif general clair, mesurable et ambitieux (1-2 phrases). Utilise un verbe d'action (contribuer, renforcer, ameliorer...).",
                'label' => 'objectif general du projet',
            ],
            'specific_objective' => [
                'instruction' => "{$base} Formule UN objectif specifique SMART (Specifique, Mesurable, Atteignable, Realiste, Temporel). 1-2 phrases maximum. Doit contribuer a l'objectif general.",
                'label' => 'objectif specifique',
            ],
            'result' => [
                'instruction' => "{$base} Formule UN resultat attendu concret et mesurable (1-2 phrases). Un resultat decrit un changement observable qui contribue a l'objectif specifique.",
                'label' => 'resultat attendu',
            ],
            'activity' => [
                'instruction' => "{$base} Decris UNE activite concrete et actionnable (2-3 phrases). Precise ce qui sera fait, comment, et pour qui. L'activite doit contribuer au resultat attendu.",
                'label' => 'description de l\'activite',
            ],
        ];

        return $prompts[$field] ?? ['instruction' => "{$base} Genere du contenu pertinent.", 'label' => $field];
    }

    /**
     * Build extra context from dynamic fields for AI.
     * E.g., for result suggestion, include the parent specific objective.
     */
    protected function getAiDynamicContext(string $field, ?int $soIndex = null, ?int $rIndex = null): string
    {
        $extra = [];
        if ($soIndex !== null && isset($this->specificObjectives[$soIndex])) {
            $so = $this->specificObjectives[$soIndex];
            if (!empty($so['description'])) {
                $extra[] = "Objectif specifique parent: {$so['description']}";
            }
            if ($rIndex !== null && isset($so['results'][$rIndex])) {
                $r = $so['results'][$rIndex];
                if (!empty($r['description'])) {
                    $extra[] = "Resultat parent: {$r['description']}";
                }
            }
        }
        return implode("\n", $extra);
    }

    public function aiGenerate(string $field, ?int $soIndex = null, ?int $rIndex = null, ?int $aIndex = null)
    {
        if (empty($this->projectTitle)) {
            $this->notifyToast('warning', __('ai.need_title'));
            return;
        }

        // Build a unique key for the active field
        $fieldKey = $field;
        if ($soIndex !== null) $fieldKey .= ".{$soIndex}";
        if ($rIndex !== null) $fieldKey .= ".{$rIndex}";
        if ($aIndex !== null) $fieldKey .= ".{$aIndex}";

        $this->aiActiveField = $fieldKey;
        $this->aiSuggestion = null;
        $this->aiLoading = true;

        $ai = app(\App\Services\AI\AiService::class);
        $context = $this->getAiContext();
        $dynamicContext = $this->getAiDynamicContext($field, $soIndex, $rIndex);
        $fieldPrompt = $this->getFieldPrompt($field);

        $fullContext = $context;
        if ($dynamicContext) {
            $fullContext .= "\n" . $dynamicContext;
        }

        $prompt = "Voici le projet :\n{$fullContext}\n\nGenere le contenu pour : {$fieldPrompt['label']}";

        $this->aiSuggestion = $ai->ask($prompt, $fieldPrompt['instruction'], 0.85);

        $this->aiLoading = false;
        if (!$this->aiSuggestion) {
            $this->aiActiveField = null;
            $this->notifyToast('danger', __('ai.error'));
        }
    }

    public function aiApply()
    {
        if (!$this->aiSuggestion || !$this->aiActiveField) return;

        $fieldKey = $this->aiActiveField;
        $parts = explode('.', $fieldKey);
        $baseField = $parts[0];

        // Dynamic fields: specific_objective.0, result.0, activity.0
        if ($baseField === 'general_objective') {
            $this->initialLogicalFramework['general_objective'] = $this->aiSuggestion;
        } elseif ($baseField === 'specific_objective' && isset($parts[1])) {
            $this->specificObjectives[$parts[1]]['description'] = $this->aiSuggestion;
        } elseif ($baseField === 'result' && isset($parts[1])) {
            // Step 4: expectedResults is a flat array
            if (isset($this->expectedResults[$parts[1]])) {
                $this->expectedResults[$parts[1]]['description'] = $this->aiSuggestion;
            }
        } elseif ($baseField === 'activity' && isset($parts[1])) {
            // Step 5: activities flat array
            if (isset($this->activities[$parts[1]])) {
                $this->activities[$parts[1]]['description'] = $this->aiSuggestion;
            }
        } elseif (property_exists($this, $baseField)) {
            $this->{$baseField} = $this->aiSuggestion;
            $this->dispatch('ai-set-editor-content', name: $baseField, content: $this->aiSuggestion);
        }

        $this->aiSuggestion = null;
        $this->aiActiveField = null;
        $this->notifyToast('success', __('ai.applied'));
    }

    public function aiRegenerate()
    {
        if (!$this->aiActiveField) return;

        $parts = explode('.', $this->aiActiveField);
        $baseField = $parts[0];
        $soIndex = isset($parts[1]) ? (int) $parts[1] : null;
        $rIndex = isset($parts[2]) ? (int) $parts[2] : null;
        $aIndex = isset($parts[3]) ? (int) $parts[3] : null;

        $this->aiGenerate($baseField, $soIndex, $rIndex, $aIndex);
    }

    public function aiDismiss()
    {
        $this->aiSuggestion = null;
        $this->aiActiveField = null;
        $this->aiLogframe = null;
    }

    public function aiSuggestLogframe()
    {
        if (empty($this->projectTitle)) {
            $this->notifyToast('warning', __('ai.need_title'));
            return;
        }
        $this->aiLoading = true;
        $this->aiLogframe = null;

        $ai = app(\App\Services\AI\AiService::class);
        $this->aiLogframe = $ai->suggestLogframe($this->projectTitle, strip_tags($this->contextDescription ?? ''));

        $this->aiLoading = false;
        if (!$this->aiLogframe) {
            $this->notifyToast('danger', __('ai.error'));
        }
    }

    public function aiApplyLogframe()
    {
        if (!$this->aiLogframe) return;

        if (isset($this->aiLogframe['general_objective'])) {
            $this->initialLogicalFramework['general_objective'] = $this->aiLogframe['general_objective'];
        }

        if (isset($this->aiLogframe['specific_objectives'])) {
            $this->specificObjectives = [];
            foreach ($this->aiLogframe['specific_objectives'] as $so) {
                $results = [];
                foreach ($so['results'] ?? [] as $r) {
                    $activities = [];
                    foreach ($r['activities'] ?? [] as $a) {
                        $activities[] = [
                            'description' => $a,
                            'responsible_user_id' => '',
                            'start_date' => '',
                            'end_date' => '',
                        ];
                    }
                    $results[] = [
                        'description' => $r['description'] ?? '',
                        'activities' => $activities,
                    ];
                }
                $this->specificObjectives[] = [
                    'description' => $so['description'] ?? '',
                    'results' => $results,
                ];
            }
        }

        $this->aiLogframe = null;
        $this->notifyToast('success', __('ai.applied'));
    }

    // =========================================================================
    // SUBMISSION & FINALIZATION
    // =========================================================================

    public function submitForm()
    {
        if ($this->isSubmitting) {
            return;
        }
        $this->isSubmitting = true;

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isSubmitting = false;
            $this->markStepsWithErrors($e->errors());

            // Build a user-friendly error summary
            $errorMessages = collect($e->errors())->flatten()->take(5)->implode(' | ');
            $errorCount = count($e->errors());
            $extra = $errorCount > 5 ? " (+".($errorCount - 5)." autres)" : "";
            $this->notifyToast('error', $errorMessages . $extra, "{$errorCount} erreur(s) de validation");

            // Navigate to the first step with errors
            foreach ($this->stepDetails as $index => $step) {
                if (!empty($step['has_error'])) {
                    $this->currentStep = $index + 1;
                    break;
                }
            }

            throw $e;
        }

        $projectDataPayload = [
            'project_code'       => $this->projectCode,
            'title'              => $this->projectTitle,
            'short_title'        => $this->projectShortTitle,
            'start_date'         => $this->projectStartDate ? Carbon::parse($this->projectStartDate)->format('Y-m-d') : null,
            'end_date'           => $this->projectEndDate ? Carbon::parse($this->projectEndDate)->format('Y-m-d') : null,
            'status'             => $this->projectStatus,
            'project_type_id'    => $this->selectedProjectTypeId ?: null,
            'general_objectives' => $this->dynamicFieldValues,
            'description'        => $this->cleanHtml($this->contextDescription),
            'problem_analysis'   => $this->cleanHtml($this->problemAnalysis),
            'strategy'           => $this->cleanHtml($this->strategy),
            'justification'      => $this->cleanHtml($this->justification),
        ];

        \Log::info('SUBMIT_FORM_START: Beginning form submission process.', ['payload' => $projectDataPayload]);

        try {
            DB::beginTransaction();

            $project = null;
            if ($this->projectId) {
                \Log::info('SUBMIT_FORM_BRANCH: Updating existing project.', ['projectId' => $this->projectId]);
                $this->updateProject($projectDataPayload);
                $project = Project::find($this->projectId);
            } else {
                \Log::info('SUBMIT_FORM_BRANCH: Creating new project.');
                $project = $this->createProject($projectDataPayload);
            }

            // Sauvegarder les documents uploades
            if ($project && !empty($this->uploadedDocuments)) {
                foreach ($this->uploadedDocuments as $document) {
                    $path = $document->store('documents/' . $project->id, 'public');
                    \App\Models\ProjectDocument::create([
                        'project_id' => $project->id,
                        'file_path' => $path,
                        'file_name' => $document->getClientOriginalName(),
                        'file_type' => $document->getMimeType(),
                        'organization_id' => $project->organization_id,
                        'creator_user_id' => Auth::id(),
                    ]);
                }
            }

            // Sauvegarder les budgets
            if ($project && !empty($this->budgets)) {
                // Supprimer les anciens budgets en edition
                if ($this->projectId) {
                    \App\Models\Budget::where('project_id', $project->id)->delete();
                }
                foreach ($this->budgets as $budgetData) {
                    if (!empty($budgetData['description'])) {
                        \App\Models\Budget::create([
                            'project_id' => $project->id,
                            'description' => $budgetData['description'],
                            'total_amount' => $budgetData['total_cost'] ?? $budgetData['total_amount'] ?? 0,
                            'organization_id' => $project->organization_id,
                            'creator_user_id' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();
            \Log::info('SUBMIT_FORM_SUCCESS: Transaction committed successfully.');

            // Notifier les org_admin de la soumission du projet
            if ($project) {
                $orgAdmins = User::where('organization_id', $project->organization_id)
                    ->where('role', \App\Enums\AccountType::ORG_ADMIN)
                    ->where('id', '!=', Auth::id())
                    ->get();

                foreach ($orgAdmins as $admin) {
                    $admin->notify(new \App\Notifications\ProjectSubmittedNotification($project, Auth::user()));
                }
            }

            $this->notifyToast('success', 'Projet enregistré avec succès.');
            return redirect()->route('project.list');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $this->isSubmitting = false;
            \Log::error('SUBMIT_FORM_VALIDATION_ERROR: Validation failed.', ['errors' => $e->errors()]);
            $this->notifyToast('error', 'La validation a échoué. Veuillez vérifier tous les onglets.');
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->isSubmitting = false;
            \Log::error('SUBMIT_FORM_FATAL_ERROR: Exception thrown during submission.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->notifyToast('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }

    private function updateProject($data)
    {
        $project = Project::findOrFail($this->projectId);
        $data['updated_by_user_id'] = Auth::id();
        $project->update($data);

        App::make(\App\Actions\UpdateLogicalFrameworkAction::class)->execute(
            $project,
            $this->initialLogicalFramework,
            $this->specificObjectives,
            $this->expectedResults,
            $this->activities
        );
    }

    private function createProject($data)
    {
        \Log::info('CREATE_PROJECT_START: Instantiating ProjectDTO.', ['data' => $data]);
        
        $dto = \App\DTOs\ProjectDTO::fromArray($data);

        \Log::info('CREATE_PROJECT_DTO_CREATED: Executing CreateProjectAction', ['dto' => (array)$dto]);
        $project = App::make(CreateProjectAction::class)->execute($dto);
        \Log::info('CREATE_PROJECT_CORE_CREATED: Core project created.', ['project_id' => $project->id ?? 'NULL']);

        \Log::info('CREATE_PROJECT_LOGFRAME: Executing CreateLogicalFrameworkAction', [
            'logicalFramework' => $this->initialLogicalFramework,
            'specificObjectivesCount' => count($this->specificObjectives),
            'expectedResultsCount' => count($this->expectedResults),
            'activitiesCount' => count($this->activities)
        ]);
        
        App::make(\App\Actions\CreateLogicalFrameworkAction::class)->execute(
            $project->id,
            $this->initialLogicalFramework,
            $this->specificObjectives,
            $this->expectedResults,
            $this->activities
        );
        
        \Log::info('CREATE_PROJECT_END: Logical framework attached successfully.');

        return $project;
    }

    private function cleanHtml($content)
    {
        if (empty($content)) return null;
        return clean($content);
    }

    private function markStepsWithErrors(array $errorKeys)
    {
        $stepPrefixes = [
            1 => ['projectTitle', 'projectCode', 'projectStartDate', 'projectEndDate', 'selectedProjectTypeId'],
            2 => ['contextDescription', 'problemAnalysis', 'strategy', 'justification', 'uploadedDocuments'],
            3 => ['initialLogicalFramework', 'specificObjectives'],
            4 => ['expectedResults'],
            5 => ['activities'],
        ];

        foreach ($this->stepDetails as $index => &$step) {
            $stepNum = $index + 1;
            $prefixes = $stepPrefixes[$stepNum] ?? [];
            $step['has_error'] = false;
            foreach (array_keys($errorKeys) as $errorKey) {
                foreach ($prefixes as $prefix) {
                    if (str_starts_with($errorKey, $prefix)) {
                        $step['has_error'] = true;
                        break 2;
                    }
                }
            }
        }
    }

    private function updateStepErrorStates()
    {
        $errors = $this->getErrorBag()->toArray();
        if (!empty($errors)) {
            $this->markStepsWithErrors($errors);
        }
    }

    public function render()
    {
        $this->updateStepErrorStates();
        return view('livewire.v-beta.proposal-project.proposal-project-form-livewire');
    }
}
