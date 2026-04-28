<?php

namespace App\Services\Queries;

use App\Models\Activity;
use App\Models\LogicalFramework;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

/**
 * LogframeQueryService — Point d'entree unique pour charger le cadre logique d'un projet.
 *
 * Centralise les eager loads pour eviter les with() repetes dans 6+ fichiers.
 *
 * Usage :
 *   LogframeQueryService::forProject($id)->full()        // tout
 *   LogframeQueryService::forProject($id)->objectives()  // objectifs seulement
 *   LogframeQueryService::forProject($id)->activities()   // activites (flat)
 *   LogframeQueryService::forProject($id)->withIndicators() // tout + indicateurs
 */
class LogframeQueryService
{
    private string $projectId;

    public function __construct(string $projectId)
    {
        $this->projectId = $projectId;
    }

    public static function forProject(string $projectId): static
    {
        return new static($projectId);
    }

    public function full(): ?LogicalFramework
    {
        return LogicalFramework::with([
            'specificObjectives.results.activities',
        ])->where('project_id', $this->projectId)->first();
    }

    public function withIndicators(): ?LogicalFramework
    {
        return LogicalFramework::with([
            'indicatorItems',
            'specificObjectives.indicatorItems',
            'specificObjectives.results.indicatorItems',
            'specificObjectives.results.activities',
        ])->where('project_id', $this->projectId)->first();
    }

    public function objectives(): Collection
    {
        $lf = $this->full();

        return $lf ? $lf->specificObjectives : new Collection();
    }

    public function results(): Collection
    {
        $lf = $this->full();
        if (!$lf) {
            return new Collection();
        }

        return $lf->specificObjectives->flatMap(fn ($obj) => $obj->results);
    }

    public function activities(): Collection
    {
        return Activity::where('project_id', $this->projectId)->get();
    }

    public function project(): ?Project
    {
        return Project::with([
            'logicalFrameworks.specificObjectives.results.activities',
            'logicalFrameworks.indicatorItems',
            'logicalFrameworks.specificObjectives.indicatorItems',
            'logicalFrameworks.specificObjectives.results.indicatorItems',
            'projectDocuments',
            'creator',
            'projectType',
        ])->find($this->projectId);
    }
}
