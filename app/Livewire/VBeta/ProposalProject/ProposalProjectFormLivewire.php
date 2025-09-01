<?php

namespace App\Livewire\VBeta\ProposalProject;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;
use App\Models\Budget;
use App\Models\Result;
use App\Models\Project;
use Livewire\Component;
use App\Models\Activity;
use App\Models\ProjectType;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\ProjectContext;
use App\Models\ProjectDocument;
use Illuminate\Validation\Rule;
use App\Models\LogicalFramework;
use App\Models\SpecificObjective;
use Illuminate\Support\Facades\DB;
use App\Models\DynamicProjectField;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Import de Carbon pour la manipulation des dates
use Illuminate\Validation\ValidationException;

class ProposalProjectFormLivewire extends Component
{
    use WithFileUploads, AuthorizesRequests;

    // Propriétés du wizard
    public $currentStep = 1;
    public $totalSteps = 7;
    public $stepDetails = [];

    // Données du projet principal (table 'projects')
    public $projectId; // Null en création, UUID en édition
    public $projectCode;
    public $projectTitle;
    public $projectShortTitle = ''; // Initialisé à une chaîne vide
    public $projectStartDate;
    public $projectEndDate;
    public $projectStatus = 'Brouillon';

    // Données pour la sélection du type de projet et les champs dynamiques
    public $allProjectTypes = [];
    public $selectedProjectTypeId;
    public $dynamicFormFields = []; // Définitions des champs dynamiques groupés par section
    public $dynamicFieldValues = []; // Valeurs saisies par l'utilisateur pour les champs dynamiques

    // Données des étapes spécifiques
    public $contextDescription;
    public $problemAnalysis;
    public $strategy;
    public $justification;
    public $uploadedDocuments = []; // Pour ProjectDocument
    public $existingDocuments = [];
    public $initialLogicalFramework = [ // Pour LogicalFramework
        'general_objective' => '',
        'general_obj_indicators' => '',
        'general_obj_verification_sources' => '',
        'assumptions' => '',
    ];
    public $specificObjectives = []; // Tableau d'objectifs spécifiques
    public $expectedResults = []; // Tableau de résultats
    public $activities = []; // Tableau d'activités
    public $budgets = []; // Tableau de lignes budgétaires

    

    

    // Liste des utilisateurs pour les responsables (dropdowns)
    public $users = [];

    // Message de statut (succès/erreur)
    public $statusMessage = '';
    public $statusType = 'hidden';

    protected $listeners = ['stepChanged'];

    /**
     * Définit les règles de validation pour le formulaire.
     *
     * @return array
     */
    protected function rules()
    {
        $rules = [
            'projectCode' => ['required', 'string', 'max:255', Rule::unique('projects', 'project_code')->ignore($this->projectId)],
            'projectTitle' => 'required|string|max:255',
            'projectStartDate' => 'required|date',
            'projectEndDate' => 'required|date|after_or_equal:projectStartDate',
            'selectedProjectTypeId' => 'required|uuid|exists:project_types,id',
            'contextDescription' => 'nullable|string',
            'problemAnalysis' => 'nullablr|string',
            'strategy' => 'nullablr|string',
            'justification' => 'nullablr|string',
            
            'uploadedDocuments.*' => 'nullable|file|max:50000', // 50MB max par fichier

            'initialLogicalFramework.general_objective' => 'required|string',
            'initialLogicalFramework.general_obj_indicators' => 'nullable|string',
            'initialLogicalFramework.general_obj_verification_sources' => 'nullable|string',
            'initialLogicalFramework.assumptions' => 'nullable|string',
            'specificObjectives.*.description' => 'required|string',
            'specificObjectives.*.indicators' => 'nullable|string',
            'specificObjectives.*.verification_sources' => 'nullable|string',
            'specificObjectives.*.assumptions' => 'nullable|string',
            'expectedResults.*.description' => 'required|string',
            'activities.*.description' => 'required|string',
            'activities.*.responsible_user_id' => 'required|uuid|exists:users,id',
            'activities.*.start_date' => 'required|date',
            'activities.*.end_date' => 'required|date|after_or_equal:activities.*.start_date',
            'activities.*.status' => 'required|string|in:En cours,Terminée,En attente,En retard',
            'budgets.*.description' => 'required|string',
            'budgets.*.quantity' => 'nullable|integer|min:0',
            'budgets.*.unit_cost' => 'nullable|numeric|min:0',
            'budgets.*.total_cost' => 'nullable|numeric|min:0',
            'budgets.*.category' => 'nullable|string',
            'budgets.*.responsible_user_id' => 'nullable|uuid|exists:users,id',
            
        ];

       // Règles dynamiques BASÉES sur l'étape courante
    $currentSection = $this->getSectionFromStep($this->currentStep);

    if ($currentSection && isset($this->dynamicFormFields[$currentSection])) {
            foreach ($this->dynamicFormFields[$currentSection] as $field) {
                $key = 'dynamicFieldValues.' . $field['field_name'];
                
                // Règle de validation standard, `required` ou `nullable`
                $validationRule = $field['is_required'] ? 'required' : 'nullable';

                // Règles spécifiques en fonction du type d'input
                if ($field['input_type'] === 'number') {
                    $validationRule .= '|numeric';
                } elseif ($field['input_type'] === 'date') {
                    $validationRule .= '|date';
                } elseif ($field['input_type'] === 'select' && $field['render_as'] === 'checkbox') {
                    // Les checkboxes renvoient un tableau, la validation doit donc le refléter
                    $validationRule .= '|array';
                }  elseif ($field['input_type'] === 'select' && isset($field['render_as']) && $field['render_as'] === 'checkbox') {
                // Les checkboxes renvoient un tableau, la validation doit donc le refléter
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
     * Définit les attributs de validation personnalisés pour des messages d'erreur plus clairs.
     *
     * @return array
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
            'specificObjectives.*.indicators' => 'indicateurs de l\'objectif spécifique',
            'specificObjectives.*.verification_sources' => 'sources de vérification de l\'objectif spécifique',
            'specificObjectives.*.assumptions' => 'hypothèses de l\'objectif spécifique',
            'expectedResults.*.description' => 'description du résultat attendu',
            'activities.*.description' => 'description de l\'activité',
            'activities.*.responsible_user_id' => 'responsable de l\'activité',
            'activities.*.start_date' => 'date de début de l\'activité',
            'activities.*.end_date' => 'date de fin de l\'activité',
            'activities.*.status' => 'statut de l\'activité',
            'budgets.*.description' => 'description de la ligne budgétaire',
            'budgets.*.quantity' => 'quantité',
            'budgets.*.unit_cost' => 'coût unitaire',
            'budgets.*.total_cost' => 'coût total',
            'budgets.*.category' => 'catégorie budgétaire',
            'budgets.*.responsible_user_id' => 'responsable budgétaire',
            'uploadedDocuments.*' => 'document', // Attribut générique pour les fichiers uploadés
        ];

        // Ajouter les attributs pour les champs dynamiques en utilisant leur question_text
        foreach ($this->dynamicFormFields as $section => $fields) {
            foreach ($fields as $field) {
                $attributes['dynamicFieldValues.' . $field['field_name']] = strtolower($field['question_text']);
            }
        }

        return $attributes;
    }

    /**
     * Méthode d'initialisation du composant.
     *
     * @param string|null $projectId L'ID du projet si en mode édition.
     * @return void
     */
    public function mount($projectId = null)
    {
        $this->users = User::all();
        $this->allProjectTypes = ProjectType::all();

        $this->stepDetails = [
            ['title' => 'Informations Clés', 'description' => 'Détails de base du projet'],
            ['title' => 'Contexte & Documents', 'description' => 'Description et fichiers pertinents'],
            ['title' => 'Cadre Logique', 'description' => 'But et objectifs spécifiques'],
            ['title' => 'Résultats Attendus', 'description' => 'Livrables concrets du projet'],
            ['title' => 'Activités Initiales', 'description' => 'Actions préliminaires du projet'],
            ['title' => 'Budget Prévisionnel', 'description' => 'Estimation des coûts initiaux'],
            ['title' => 'Finalisation', 'description' => 'Vérification et soumission'],
        ];

        $this->dynamicFieldValues = []; // Initialisation du tableau

        if ($projectId) {

            $this->projectId = $projectId;
            $project = Project::with([
                'projectContext',
                'projectDocuments',
                'logicalFramework.specificObjectives.results',
                'logicalFramework.specificObjectives.results.activities',
                'budgets',
                'projectType'
            ])->find($projectId);
            // Vérifie que l'utilisateur peut créer OU mettre à jour
        
            $this->authorize('update', $project);

            if (!$project) {
                $this->projectId = null;
                $this->addSpecificObjective();
                $this->addExpectedResult();
                $this->addActivity();
                $this->addBudget();
                return;
            }

            $this->existingDocuments = $project->projectDocuments;

            $this->projectCode = $project->project_code;
            $this->projectTitle = $project->title;
            $this->projectShortTitle = $project->short_title;

            $this->problemAnalysis = $project->problem_analysis;
            $this->strategy = $project->strategy;
            $this->justification = $project->justification;

            

            // Correction ici : toujours formater la date au format Y-m-d
            $this->projectStartDate = $project->start_date ? Carbon::parse($project->start_date)->format('Y-m-d') : null;
            $this->projectEndDate = $project->end_date ? Carbon::parse($project->end_date)->format('Y-m-d') : null;

            $this->projectStatus = $project->status;
            $this->selectedProjectTypeId = $project->project_type_id;

            $projectType = ProjectType::with('dynamicFields')->findOrFail($this->selectedProjectTypeId);
            $this->dynamicFormFields = $projectType->dynamicFields->groupBy('section')->toArray();

            // Récupérer le contenu du champ qui stocke toutes les données dynamiques
            $dynamicFieldsString = $project->general_objectives;

            // Si le champ n'est pas vide, extraire les valeurs pour chaque champ dynamique
            if (!empty($dynamicFieldsString)) {
                foreach ($projectType->dynamicFields as $fieldDef) {
                    $fieldName = $fieldDef['field_name'];
                    $delimiterStart = $fieldDef['delimiter_start'];
                    $delimiterEnd = $fieldDef['delimiter_end'];

                    $pattern = '/' . preg_quote($delimiterStart, '/') . '(.*?)' . preg_quote($delimiterEnd, '/') . '/s';
                    
                    if (preg_match($pattern, $dynamicFieldsString, $matches)) {
                        $value = $matches[1];
                        // Gérer les cas spécifiques comme les checkboxes
                        if ($fieldDef['input_type'] === 'select' && $fieldDef['render_as'] === 'checkbox') {
                            $this->dynamicFieldValues[$fieldName] = json_decode($value);
                        } else {
                            $this->dynamicFieldValues[$fieldName] = $value;
                        }
                    }
                }
            }
                
                // Charger les données des relations
            $this->contextDescription = $project->projectContext->context_description ?? '';
                
            if ($project->logicalFramework) {
                $this->initialLogicalFramework = $project->logicalFramework->toArray();
                $this->specificObjectives = $project->logicalFramework->specificObjectives->toArray();
                
                // Remplir expectedResults en se basant sur les résultats liés aux objectifs spécifiques
                $this->expectedResults = [];
                foreach ($project->logicalFramework->specificObjectives as $obj) {
                    foreach ($obj->results as $res) {
                        $this->expectedResults[] = $res->toArray();
                    }
                }
            } else {
                // Si pas de cadre logique, initialiser pour éviter les erreurs
                $this->addSpecificObjective();
                $this->addExpectedResult();
            }

                
            $this->activities = [];
            foreach ($project->logicalFramework->specificObjectives as $obj) {
                foreach ($obj->results as $res) {
                    foreach ($res->activities as $act) {
                        $activity = $act->toArray();
                        // S'assurer que les dates sont formatées correctement
                        if (isset($activity['start_date'])) {
                            $activity['start_date'] = Carbon::parse($activity['start_date'])->format('Y-m-d');
                        }
                        if (isset($activity['end_date'])) {
                            $activity['end_date'] = Carbon::parse($activity['end_date'])->format('Y-m-d');
                        }
                        $this->activities[] = $activity;
                    }
                }
            }
            $this->budgets = $project->budgets->toArray();

        } else {
                // Initialisation pour la création
                Log::debug("Création d'un nouveau projet, initialisation des propriétés.");
                $this->projectStartDate = null; // Initialisation explicite à null
                $this->projectEndDate = null;   // Initialisation explicite à null
                $this->addSpecificObjective();
                $this->addExpectedResult();
                $this->addActivity();
                $this->addBudget();
            }

            Log::debug("Dynamic Fields Preview", [
                'sections' => array_keys($this->dynamicFormFields),
                'first_field' => $this->dynamicFormFields[array_key_first($this->dynamicFormFields)][0] ?? null,
                'values' => $this->dynamicFieldValues
            ]);

            // Charger les champs dynamiques SI un type est déjà sélectionné
            if ($this->selectedProjectTypeId) {
                $this->loadDynamicFields();
                // Ici, il faut s'assurer que les valeurs sont prêtes pour les checkboxes
                $this->initializeDynamicFieldValues();
            }
            
    }

    private function extractDynamicFieldValues(Project $project, $dynamicFields)
    {
        foreach ($dynamicFields as $field) {
            $targetProjectField = $field->target_project_field;
            $delimiterStart = $field->delimiter_start;
            $delimiterEnd = $field->delimiter_end;
            $fieldName = $field->field_name;

            // On récupère le contenu complet de la colonne du projet
            $fullContent = $project->$targetProjectField;
            
            // On s'assure que le contenu n'est pas null et que les délimiteurs sont définis
            if (!is_null($fullContent) && !is_null($delimiterStart) && !is_null($delimiterEnd)) {
                // Créer une expression régulière pour extraire le contenu entre les délimiteurs
                $pattern = '/' . preg_quote($delimiterStart, '/') . '(.*?)' . preg_quote($delimiterEnd, '/') . '/s';
                
                if (preg_match($pattern, $fullContent, $matches)) {
                    $value = $matches[1];
                    // Si le champ est une checkbox (stocké en JSON), on décode
                    if ($field->input_type === 'select' && $field->render_as === 'checkbox') {
                        $this->dynamicFieldValues[$fieldName] = json_decode($value, true) ?? [];
                    } else {
                        $this->dynamicFieldValues[$fieldName] = $value;
                    }
                } else {
                    // Si aucune correspondance n'est trouvée, on met une valeur par défaut
                    $this->dynamicFieldValues[$fieldName] = null;
                }
            } else {
                 $this->dynamicFieldValues[$fieldName] = null;
            }
        }
    }


    /**
 * Initialise les valeurs des champs dynamiques, en particulier les tableaux pour les checkboxes.
 *
 * @return void
 */
    private function initializeDynamicFieldValues()
    {
        foreach ($this->dynamicFormFields as $section => $fields) {
            foreach ($fields as $fieldDef) {
                if ($fieldDef['input_type'] === 'select' && isset($fieldDef['render_as']) && $fieldDef['render_as'] === 'checkbox') {
                    if (!isset($this->dynamicFieldValues[$fieldDef['field_name']])) {
                        $this->dynamicFieldValues[$fieldDef['field_name']] = [];
                    }
                }
            }
        }
    }

    /**
     * Méthode appelée quand le type de projet est sélectionné ou mis à jour.
     *
     * @param string $value L'ID du type de projet sélectionné.
     * @return void
     */
    public function updatedSelectedProjectTypeId($value)
    {
        $this->loadDynamicFields();
        // Réinitialiser les valeurs des champs dynamiques si le type de projet change
        $this->dynamicFieldValues = [];

         // Initialisation des champs checkbox à des tableaux vides pour éviter les erreurs
                foreach ($this->dynamicFormFields as $section => $fields) {
                    foreach ($fields as $fieldDef) {
                        if ($fieldDef['input_type'] === 'select' && isset($fieldDef['render_as']) && $fieldDef['render_as'] === 'checkbox') {
                            $this->dynamicFieldValues[$fieldDef['field_name']] = [];
                        }
                    }
                }
    }

    /**
     * Charge les définitions des champs dynamiques pour le type de projet sélectionné,
     * en les groupant par section.
     *
     * @return void
     */
    public function loadDynamicFields()
    {
        if (!$this->selectedProjectTypeId) {
            $this->dynamicFormFields = [];
            return;
        }

        // Debug crucial
        logger()->debug("Chargement des champs pour type", [
            'type_id' => $this->selectedProjectTypeId,
            'exists' => ProjectType::where('id', $this->selectedProjectTypeId)->exists()
        ]);

        // $fields = DynamicProjectField::where('project_type_id', $this->selectedProjectTypeId)
        //     ->orderBy('order')
        //     ->get()
        //     ->groupBy('section')
        //     ->toArray();

        $fields = DynamicProjectField::where('project_type_id', $this->selectedProjectTypeId)
        ->orderBy('order')
        ->get()
        ->map(function($field) {
            // C'est cette ligne qui est la plus importante.
            // On vérifie que `options` est une chaîne non vide.
            // Si c'est le cas, on la décode en tableau PHP, sinon on initialise un tableau vide.
            // CORRECTION: Décodage direct des options JSON
            if ($field->input_type === 'select' && $field->options) {
                $field->options = json_decode($field->options, true) ?? [];
            } else {
                $field->options = [];
            }
            return $field;
        })
        ->groupBy('section')
        ->toArray();


        
        $this->dynamicFormFields = $fields;

        $this->dynamicFormFields = collect($this->dynamicFormFields)
        ->filter(fn($fields, $section) => !empty($section) && count($fields) > 0)
        ->toArray();
    }

    /**
     * Passe à l'étape suivante du formulaire.
     *
     * @return void
     */
    public function nextStep()
    {
        try {
            $this->validateCurrentStep();
            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;
            }
            $this->dispatch('stepChanged'); // Émettre un événement pour Alpine.js
            Log::debug("Passage à l'étape suivante: {$this->currentStep}");
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation à l\'étape ' . $this->currentStep . ': ' . $e->getMessage(), ['errors' => $e->errors()]);
            // Livewire gère automatiquement l'affichage des erreurs de validation dans la vue
        } catch (\Exception $e) {
            Log::error('Erreur inattendue lors du passage à l\'étape suivante: ' . $e->getMessage());
            session()->flash('error', 'Une erreur inattendue est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Revient à l'étape précédente du formulaire.
     *
     * @return void
     */
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
        $this->dispatch('stepChanged');
        Log::debug("Retour à l'étape précédente: {$this->currentStep}");
    }

    /**
     * Permet de naviguer directement à une étape spécifique.
     *
     * @param int $step Le numéro de l'étape cible.
     * @return void
     */
    public function goToStep($step)
    {
        // Optionnel: Valider les étapes précédentes avant de sauter
        // Pour l'instant, on permet de sauter si l'étape est déjà passée.
        if ($step >= 1 && $step <= $this->totalSteps) {
            $this->currentStep = $step;
            $this->dispatch('stepChanged');
            Log::debug("Navigation directe à l'étape: {$this->currentStep}");
        }
    }

    /**
     * Valide uniquement les champs de l'étape en cours.
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateCurrentStep()
    {
        // Validation des champs de base pour chaque étape
        switch ($this->currentStep) {
            case 1:
                $this->validateOnly('selectedProjectTypeId');
                $this->validateOnly('projectCode');
                $this->validateOnly('projectTitle');
                $this->validateOnly('projectStartDate');
                $this->validateOnly('projectEndDate');
                break;
            case 2:
                $this->validateOnly('contextDescription');
                if (!empty($this->uploadedDocuments)) {
                    $this->validateOnly('uploadedDocuments.*');
                }
                break;
            case 3:
                $this->validateOnly('initialLogicalFramework.general_objective');
                $this->validateOnly('initialLogicalFramework.general_obj_indicators');
                $this->validateOnly('initialLogicalFramework.general_obj_verification_sources');
                $this->validateOnly('initialLogicalFramework.assumptions');
                
                foreach ($this->specificObjectives as $index => $objective) {
                    $this->validateOnly('specificObjectives.' . $index . '.description');
                    $this->validateOnly('specificObjectives.' . $index . '.indicators');
                    $this->validateOnly('specificObjectives.' . $index . '.verification_sources');
                    $this->validateOnly('specificObjectives.' . $index . '.assumptions');
                }
                break;
            case 4:
                foreach ($this->expectedResults as $index => $result) {
                    $this->validateOnly('expectedResults.' . $index . '.description');
                }
                break;
            case 5:
                foreach ($this->activities as $index => $activity) {
                    $this->validateOnly('activities.' . $index . '.description');
                    $this->validateOnly('activities.' . $index . '.responsible_user_id');
                    $this->validateOnly('activities.' . $index . '.start_date');
                    $this->validateOnly('activities.' . $index . '.end_date');
                    $this->validateOnly('activities.' . $index . '.status');
                }
                break;
            case 6:
                foreach ($this->budgets as $index => $budget) {
                    $this->validateOnly('budgets.' . $index . '.description');
                    $this->validateOnly('budgets.' . $index . '.quantity');
                    $this->validateOnly('budgets.' . $index . '.unit_cost');
                    $this->validateOnly('budgets.' . $index . '.total_cost');
                    $this->validateOnly('budgets.' . $index . '.category');
                    $this->validateOnly('budgets.' . $index . '.responsible_user_id');
                }
                break;
            // Pas de validation pour l'étape 7 (finalisation)
        }

        // Validation des champs dynamiques POUR L'ÉTAPE COURANTE SEULEMENT
        $sectionName = $this->getSectionFromStep($this->currentStep);
        if (isset($this->dynamicFormFields[$sectionName])) {
            $dynamicRules = [];
            
            foreach ($this->dynamicFormFields[$sectionName] as $field) {
                $key = 'dynamicFieldValues.' . $field['field_name'];
                $dynamicRules[$key] = $field['is_required'] ? 'required|string' : 'nullable|string';
                
                // Ajouter des règles spécifiques si nécessaire
                if ($field['input_type'] === 'number') {
                    $dynamicRules[$key] .= '|numeric';
                } elseif ($field['input_type'] === 'date') {
                    $dynamicRules[$key] .= '|date';
                }
            }
            
            $this->validate($dynamicRules);
        }
    }
    
    /**
     * Retourne le nom de la section associée à un numéro d'étape.
     *
     * @param int $step Le numéro de l'étape.
     * @return string|null
     */
    private function getSectionFromStep(int $step): ?string
    {
        $stepSectionMap = [
            1 => 'informations_cles',
            2 => 'contexte_documents',
            3 => 'cadre_logique',
            4 => 'resultats_attendus',
            5 => 'activites_initiales',
            6 => 'budget_previsionnel',
            7 => 'finalisation', // Bien que non utilisé pour les champs dynamiques, utile pour la cohérence
        ];
        return $stepSectionMap[$step] ?? null;
    }

    /**
     * Ajoute un nouvel objectif spécifique au tableau.
     *
     * @return void
     */
    public function addSpecificObjective()
    {
        $this->specificObjectives[] = [
            'id' => Str::uuid()->toString(),
            'description' => '',
            'indicators' => '',
            'verification_sources' => '',
            'assumptions' => '',
        ];
    }

    /**
     * Supprime un objectif spécifique du tableau.
     *
     * @param int $index L'index de l'objectif à supprimer.
     * @return void
     */
    public function removeSpecificObjective($index)
    {
        unset($this->specificObjectives[$index]);
        $this->specificObjectives = array_values($this->specificObjectives);
    }

    /**
     * Ajoute un nouveau résultat attendu au tableau.
     *
     * @return void
     */
    public function addExpectedResult()
    {
        $this->expectedResults[] = [
            'id' => Str::uuid()->toString(),
            'description' => '',
        ];
    }

    /**
     * Supprime un résultat attendu du tableau.
     *
     * @param int $index L'index du résultat à supprimer.
     * @return void
     */
    public function removeExpectedResult($index)
    {
        unset($this->expectedResults[$index]);
        $this->expectedResults = array_values($this->expectedResults);
    }

    /**
     * Ajoute une nouvelle activité au tableau.
     *
     * @return void
     */
    public function addActivity()
    {
        $this->activities[] = [
            'id' => null,
            'description' => '',
            'responsible_user_id' => '',
            'start_date' => '',
            'end_date' => '',
            'status' => 'En cours',
            'justification' => '',
            'is_milestone' => false,
            'progress_percentage' => 0,
        ];
    }

    /**
     * Supprime une activité du tableau.
     *
     * @param int $index L'index de l'activité à supprimer.
     * @return void
     */
    public function removeActivity($index)
    {
        unset($this->activities[$index]);
        $this->activities = array_values($this->activities);
    }

    /**
     * Ajoute une nouvelle ligne budgétaire au tableau.
     *
     * @return void
     */
    public function addBudget()
    {
        $this->budgets[] = [
            'id' => Str::uuid()->toString(),
            'description' => '',
            'quantity' => null,
            'unit_cost' => null,
            'total_cost' => null,
            'category' => '',
            'responsible_user_id' => null,
        ];
    }

    /**
     * Supprime une ligne budgétaire du tableau.
     *
     * @param int $index L'index de la ligne budgétaire à supprimer.
     * @return void
     */
    public function removeBudget($index)
    {
        unset($this->budgets[$index]);
        $this->budgets = array_values($this->budgets);
    }


    // Private methods for intelligent synchronization
    private function syncProjectData($project, $projectData)
    {
        $project->update($projectData);
        Log::debug("Projet mis à jour avec l'ID: {$project->id}");
        return $project;
    }

    private function syncLogicalFramework($project)
    {
        if ($project->logicalFramework) {
            $project->logicalFramework->update($this->initialLogicalFramework);
            return $project->logicalFramework;
        }
        
        return LogicalFramework::create(array_merge(
            ['id' => (string) Str::uuid(), 'project_id' => $project->id],
            $this->initialLogicalFramework
        ));
    }

    private function syncSpecificObjectives($logicalFramework)
    {
        $existingIds = $logicalFramework->specificObjectives->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($this->specificObjectives as $objData) {
            $cleanData = Arr::except($objData, ['id', 'logical_framework_id', 'created_at', 'updated_at', 'results']);
            
            if (isset($objData['id']) && in_array($objData['id'], $existingIds)) {
                // Mise à jour de l'objectif existant
                SpecificObjective::where('id', $objData['id'])->update($cleanData);
                $submittedIds[] = $objData['id'];
            } else {
                // Création d'un nouvel objectif
                $objective = SpecificObjective::create(array_merge(
                    $cleanData,
                    ['id' => (string) Str::uuid(), 'logical_framework_id' => $logicalFramework->id]
                ));
                $submittedIds[] = $objective->id;
            }
        }

        // Supprimer seulement les objectifs qui n'ont pas été soumis
        $toDelete = array_diff($existingIds, $submittedIds);
        if (!empty($toDelete)) {
            SpecificObjective::whereIn('id', $toDelete)->delete();
        }
    }

    private function syncExpectedResults($objectives)
    {
        // Récupérer tous les IDs de résultats existants
        $allExistingResultIds = [];
        $objectiveResultsMap = [];
        
        foreach ($objectives as $objective) {
            $resultIds = $objective->results->pluck('id')->toArray();
            $allExistingResultIds = array_merge($allExistingResultIds, $resultIds);
            $objectiveResultsMap[$objective->id] = $resultIds;
        }

        $submittedResultIds = [];
        $objectiveIndex = 0;

        foreach ($this->expectedResults as $resData) {
            $cleanData = Arr::except($resData, ['id', 'specific_objective_id', 'created_at', 'updated_at', 'activities']);
            $objective = $objectives[$objectiveIndex % count($objectives)];
            
            if (isset($resData['id']) && in_array($resData['id'], $allExistingResultIds)) {
                // Mise à jour du résultat existant
                Result::where('id', $resData['id'])->update($cleanData);
                $submittedResultIds[] = $resData['id'];
            } else {
                // Création d'un nouveau résultat
                $result = Result::create(array_merge(
                    $cleanData,
                    ['id' => (string) Str::uuid(), 'specific_objective_id' => $objective->id]
                ));
                $submittedResultIds[] = $result->id;
            }
            
            $objectiveIndex++;
        }

        // Supprimer seulement les résultats qui n'ont pas été soumis
        $toDelete = array_diff($allExistingResultIds, $submittedResultIds);
        if (!empty($toDelete)) {
            Result::whereIn('id', $toDelete)->delete();
        }
    }

    private function syncActivities($logicalFramework)
    {
        // Récupérer tous les IDs d'activités existantes
        $allExistingActivityIds = [];
        foreach ($logicalFramework->specificObjectives as $objective) {
            foreach ($objective->results as $result) {
                $activityIds = $result->activities->pluck('id')->toArray();
                $allExistingActivityIds = array_merge($allExistingActivityIds, $activityIds);
            }
        }
        

        $submittedActivityIds = [];
        $resultIndex = 0;
        $allResults = $logicalFramework->specificObjectives->flatMap->results;

        foreach ($this->activities as $activityData) {
            $cleanData = Arr::except($activityData, ['id', 'result_id', 'created_at', 'updated_at']);
            
            // Formater les dates
            if (isset($cleanData['start_date'])) {
                $cleanData['start_date'] = Carbon::parse($cleanData['start_date'])->format('Y-m-d');
            }
            if (isset($cleanData['end_date'])) {
                $cleanData['end_date'] = Carbon::parse($cleanData['end_date'])->format('Y-m-d');
            }

            $result = $allResults[$resultIndex % count($allResults)];
            
            
            if (isset($activityData['id']) && in_array($activityData['id'], $allExistingActivityIds)) {
                // Mise à jour de l'activité existante
                Activity::where('id', $activityData['id'])->update($cleanData);
                $submittedActivityIds[] = $activityData['id'];
            } else {
                // Création d'une nouvelle activité
                $activity = Activity::create(array_merge(
                    $cleanData,
                    ['id' => (string) Str::uuid(), 'result_id' => $result->id]
                ));
                $submittedActivityIds[] = $activity->id;
            }
            
            $resultIndex++;
        }


        // Supprimer seulement les activités qui n'ont pas été soumis
        $toDelete = array_diff($allExistingActivityIds, $submittedActivityIds);
        if (!empty($toDelete)) {
            Activity::whereIn('id', $toDelete)->delete();
        }
    }

    private function syncBudgets($project)
    {
        $existingIds = $project->budgets->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($this->budgets as $budgetData) {
            $cleanData = Arr::except($budgetData, ['id', 'project_id', 'created_at', 'updated_at']);
            
            if (isset($budgetData['id']) && in_array($budgetData['id'], $existingIds)) {
                // Mise à jour du budget existant
                Budget::where('id', $budgetData['id'])->update($cleanData);
                $submittedIds[] = $budgetData['id'];
            } else {
                // Création d'un nouveau budget
                $budget = Budget::create(array_merge(
                    $cleanData,
                    ['id' => (string) Str::uuid(), 'project_id' => $project->id]
                ));
                $submittedIds[] = $budget->id;
            }
        }

        // Supprimer seulement les budgets qui n'ont pas été soumis
        $toDelete = array_diff($existingIds, $submittedIds);
        if (!empty($toDelete)) {
            Budget::whereIn('id', $toDelete)->delete();
        }
    }

    /**
     * Soumet le formulaire complet et sauvegarde les données du projet.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForm()
    {
        $this->validate([
            'projectTitle' => 'required|string|max:255',
            'projectCode' => 'nullable|string|max:100',
            'projectShortTitle' => 'nullable|string|max:255',
            'projectStartDate' => 'nullable|date',
            'projectEndDate' => 'nullable|date|after_or_equal:projectStartDate',
            'projectStatus' => 'required|string',
            'selectedProjectTypeId' => 'required|exists:project_types,id',
        ]);

        $startDate = $this->projectStartDate ? Carbon::parse($this->projectStartDate)->format('Y-m-d') : null;
        $endDate   = $this->projectEndDate ? Carbon::parse($this->projectEndDate)->format('Y-m-d') : null;

        // Concaténer champs dynamiques
        $dynamicFieldsString = '';
        foreach ($this->dynamicFieldValues as $fieldName => $value) {
            $fieldDef = collect($this->dynamicFormFields)->flatten(1)->firstWhere('field_name', $fieldName);
            if ($fieldDef) {
                $delimiterStart = $fieldDef['delimiter_start'];
                $delimiterEnd   = $fieldDef['delimiter_end'];

                if ($fieldDef['input_type'] === 'select' && $fieldDef['render_as'] === 'checkbox') {
                    $value = json_encode($value ?? []);
                }

                $dynamicFieldsString .= $delimiterStart . $value . $delimiterEnd;
            }
        }

        if ($this->projectId) {
            // ----------------------
            // EDITION
            // ----------------------
            $project = Project::with([
                'logicalFramework.specificObjectives.results.activities',
                'budgets'
            ])->find($this->projectId);

            if ($project) {
                $projectData = [
                    'project_code'       => $this->projectCode,
                    'title'              => $this->projectTitle,
                    'short_title'        => $this->projectShortTitle,
                    'start_date'         => $startDate,
                    'end_date'           => $endDate,
                    'status'             => $this->projectStatus,
                    
                    'project_type_id'    => $this->selectedProjectTypeId,
                    'updated_by_user_id' => Auth::id(),
                    'general_objectives' => $dynamicFieldsString,
                    
                    'problemAnalysis' => $this->problemAnalysis,
                    'strategy' => $this->strategy,
                    'justification' => $this->justification,
                ];

                // 🔹 Log avant sync pour debug
                Log::debug('Before sync — submitted ids', [
                    'specificObjectives' => array_map(fn($o) => $o['id'] ?? null, $this->specificObjectives),
                    'expectedResults'    => array_map(fn($r) => $r['id'] ?? null, $this->expectedResults),
                    'activities'         => array_map(fn($a) => $a['id'] ?? null, $this->activities),
                    'budgets'            => array_map(fn($b) => $b['id'] ?? null, $this->budgets),
                ]);

                // 🔹 Mettre à jour projet
                $this->syncProjectData($project, $projectData);

                $this->contextDescription = $project->projectContext->context_description ?? '';

                // 🔹 Mettre à jour cadre logique
                $logicalFramework = $this->syncLogicalFramework($project);

                // 🔹 Mettre à jour objectifs spécifiques
                $this->syncSpecificObjectives($logicalFramework);

                // Recharger avant résultats
                $logicalFramework->load('specificObjectives.results');

                // 🔹 Mettre à jour résultats
                $this->syncExpectedResults($logicalFramework->specificObjectives);

                // 🔹 Mettre à jour activités
                $this->syncActivities($logicalFramework);

                // 🔹 Mettre à jour budgets
                $this->syncBudgets($project);
            }

        } else {
            // ----------------------
            // CREATION
            // ----------------------
            $project = Project::create([
                'id'                => (string) Str::uuid(),
                'project_code'      => $this->projectCode,
                'title'             => $this->projectTitle,
                'short_title'       => $this->projectShortTitle,
                'start_date'        => $startDate,
                'end_date'          => $endDate,
                'status'            => $this->projectStatus,
                'creator_user_id'   => Auth::id(),
                'project_type_id'   => $this->selectedProjectTypeId,
                'general_objectives'=> $dynamicFieldsString,

                'problem_analysis' => $this->problemAnalysis,
                'strategy' => $this->strategy,
                'justification' => $this->justification,
            ]);

            $this->projectId = $project->id;

            // 🔹 Créer cadre logique
            $logicalFramework = LogicalFramework::create(array_merge(
                ['id' => (string) Str::uuid(), 'project_id' => $project->id],
                $this->initialLogicalFramework
            ));

            $this->contextDescription = $project->projectContext->context_description ?? '';

            // 🔹 Objectifs spécifiques
            foreach ($this->specificObjectives as $objData) {
                if (empty(trim($objData['description'] ?? ''))) continue;

                $objective = SpecificObjective::create([
                    'id'                   => (string) Str::uuid(),
                    'logical_framework_id' => $logicalFramework->id,
                    'description'          => $objData['description'],
                ]);

                foreach ($this->expectedResults as $resData) {
                    if (empty(trim($resData['description'] ?? ''))) continue;

                    $result = Result::create([
                        'id'                   => (string) Str::uuid(),
                        'specific_objective_id'=> $objective->id,
                        'description'          => $resData['description'],
                    ]);

                    foreach ($this->activities as $actData) {
                        if (empty(trim($actData['description'] ?? ''))) continue;

                        Activity::create([
                            'id'        => (string) Str::uuid(),
                            'result_id' => $result->id,
                            'description'=> $actData['description'],
                            'responsible_user_id'=> $actData['responsible_user_id'],
                            'budget'=> $actData['budget'],
                            'is_milestone'=> $actData['is_milestone'],
                            'start_date'=> !empty($actData['start_date']) ? Carbon::parse($actData['start_date'])->format('Y-m-d') : null,
                            'end_date'  => !empty($actData['end_date']) ? Carbon::parse($actData['end_date'])->format('Y-m-d') : null,
                        ]);
                    }
                }
            }

            // 🔹 Budgets
            foreach ($this->budgets as $budgetData) {
                if (empty(trim($budgetData['description'] ?? ''))) continue;

                Budget::create([
                    'id'         => (string) Str::uuid(),
                    'project_id' => $project->id,
                    'description'=> $budgetData['description'],
                    'amount'     => $budgetData['amount'] ?? 0,
                ]);
            }
        }

        return redirect()->route('project.list')->with('success', $this->projectId ? 'Projet mis à jour avec succès.' : 'Projet créé avec succès.');
        // session()->flash('message', $this->projectId ? 'Projet mis à jour avec succès.' : 'Projet créé avec succès.');
    }

    public function deleteProject()
    {
        
    }


    /**
     * Gère la confirmation de suppression d'un document existant.
     * @param string $documentId
     */
    public function confirmDeleteDocument($documentId)
    {
        $this->dispatch('confirm-delete-document', ['documentId' => $documentId]);
    }

    /**
     * Supprime un fichier qui vient d'être uploadé (avant la sauvegarde).
     * @param int $index
     */
    public function removeUploadedFile($index)
    {
        // Livewire permet de manipuler directement le tableau.
        unset($this->uploadedDocuments[$index]);
        $this->uploadedDocuments = array_values($this->uploadedDocuments);
    }
    
    /**
     * Supprime un document existant de la base de données et du stockage.
     * @param string $documentId
     */
    public function removeExistingDocument($documentId)
    {
        try {
            DB::beginTransaction();
            $document = ProjectDocument::findOrFail($documentId);
            
            // Supprimer le fichier physique
            Storage::disk('public')->delete($document->file_path);
            
            // Supprimer l'entrée de la base de données
            $document->delete();
            
            // Rafraîchir la liste des documents existants
            $this->existingDocuments = $this->existingDocuments->where('id', '!=', $documentId);
            
            DB::commit();
            session()->flash('success', 'Le document a été supprimé.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur lors de la suppression du document.');
        }
    }

    /**
     * Rend la vue du composant.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.v-beta.proposal-project.proposal-project-form-livewire');
    }
}
