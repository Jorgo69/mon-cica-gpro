<?php

namespace App\Livewire\VBeta\Project;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectContext;
use App\Models\ProjectDocument;
use App\Models\LogicalFramework;
use App\Models\SpecificObjective;
use App\Models\Result;
use App\Models\Activity;
use App\Models\Risk;
use App\Models\Budget;
use App\Models\DynamicProjectField;
use App\Enums\LogframeDisplayFormat;

class ProjectShowLivewire extends Component
{
    use AuthorizesRequests;

    public $projectId;
    public $project;
    public $dynamicFormFields = [];
    public $activeTab = 'overview'; // [overview, logframe, documents, history]
    public $logframeFormat = 'cards'; // default format

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setLogframeFormat(string $format)
    {
        if (LogframeDisplayFormat::tryFrom($format)) {
            $this->logframeFormat = $format;
        }
    }

    /**
     * Monte le composant avec l'ID du projet.
     * Utilise l'eager loading pour charger toutes les relations nécessaires.
     */
    public function mount($projectId)
    {
        $this->projectId = $projectId;
        
        
        $this->loadProject();
        $this->authorize('view', $this->project);
    }

    /**
     * Charge le projet et toutes ses relations.
     *
     * J'ai mis à jour les relations pour qu'elles correspondent à votre modèle
     * et à votre formulaire de création/édition.
     */
    public function loadProject()
    {
        $this->project = \App\Services\Queries\LogframeQueryService::forProject($this->projectId)->project();

        if (!$this->project) {
            abort(404);
        }

        // Charge les définitions des champs dynamiques pour l'affichage
        if ($this->project->projectType) {
             $this->dynamicFormFields = $this->project->projectType->dynamicFields()
                ->orderBy('order')
                ->get()
                ->groupBy('section')
                ->toArray();
        }
    }

    

    /**
     * Rend la vue du composant.
     */
    public function render()
    {
        return view('livewire.v-beta.project.project-show-livewire');
    }
}
