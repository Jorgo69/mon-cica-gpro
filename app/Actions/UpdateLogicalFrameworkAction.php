<?php

namespace App\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\LogicalFramework;
use App\Models\SpecificObjective;
use App\Models\Result;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class UpdateLogicalFrameworkAction
{
    /**
     * Met à jour le cadre logique et ses enfants pour un projet donné.
     * Cette méthode gère les créations, mises à jour et suppressions (synchronisation).
     */
    public function execute(
        Project $project, 
        array $logicalFrameworkData, 
        array $specificObjectivesData, 
        array $expectedResultsData, 
        array $activitiesData
    ): LogicalFramework {
        return DB::transaction(function () use (
            $project, 
            $logicalFrameworkData, 
            $specificObjectivesData, 
            $expectedResultsData, 
            $activitiesData
        ) {
            // 🔹 1. Mettre à jour cadre logique
            $logicalFramework = $this->syncLogicalFramework($project, $logicalFrameworkData);

            // 🔹 2. Mettre à jour objectifs spécifiques
            $this->syncSpecificObjectives($logicalFramework, $specificObjectivesData);

            // Recharger les relations avant la suite pour avoir les IDs à jour
            $logicalFramework->load('specificObjectives.results');

            // 🔹 3. Mettre à jour résultats attendus
            $this->syncExpectedResults($logicalFramework->specificObjectives, $expectedResultsData);

            // Recharger pour les activités
            $logicalFramework->load('specificObjectives.results.activities');

            // 🔹 4. Mettre à jour activités
            $this->syncActivities($logicalFramework, $activitiesData);

            return $logicalFramework;
        });
    }

    private function syncLogicalFramework(Project $project, array $data): LogicalFramework
    {
        if ($project->logicalFramework) {
            $project->logicalFramework->update($data);
            return $project->logicalFramework;
        }
        
        return LogicalFramework::create(array_merge(
            ['id' => (string) Str::uuid(), 'project_id' => $project->id],
            $data
        ));
    }

    private function syncSpecificObjectives(LogicalFramework $logicalFramework, array $specificObjectivesData): void
    {
        $existingIds = $logicalFramework->specificObjectives->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($specificObjectivesData as $objData) {
            $cleanData = Arr::except($objData, ['id', 'logical_framework_id', 'created_at', 'updated_at', 'results']);
            
            if (isset($objData['id']) && in_array($objData['id'], $existingIds)) {
                SpecificObjective::where('id', $objData['id'])->update($cleanData);
                $submittedIds[] = $objData['id'];
            } else {
                if (empty(trim($cleanData['description'] ?? ''))) continue;

                $objective = SpecificObjective::create(array_merge(
                    $cleanData,
                    ['id' => (string) Str::uuid(), 'logical_framework_id' => $logicalFramework->id]
                ));
                $submittedIds[] = $objective->id;
            }
        }

        $toDelete = array_diff($existingIds, $submittedIds);
        if (!empty($toDelete)) {
            SpecificObjective::whereIn('id', $toDelete)->delete();
        }
    }

    private function syncExpectedResults($objectives, array $expectedResultsData): void
    {
        $allExistingResultIds = [];
        foreach ($objectives as $objective) {
            $resultIds = $objective->results->pluck('id')->toArray();
            $allExistingResultIds = array_merge($allExistingResultIds, $resultIds);
        }

        $submittedResultIds = [];
        $objectiveIndex = 0;

        foreach ($expectedResultsData as $resData) {
            $cleanData = Arr::except($resData, ['id', 'specific_objective_id', 'created_at', 'updated_at', 'activities']);
            
            if ($objectives->isEmpty()) break;
            $objective = $objectives[$objectiveIndex % count($objectives)];
            
            if (isset($resData['id']) && in_array($resData['id'], $allExistingResultIds)) {
                Result::where('id', $resData['id'])->update($cleanData);
                $submittedResultIds[] = $resData['id'];
            } else {
                if (empty(trim($cleanData['description'] ?? ''))) continue;

                $result = Result::create(array_merge(
                    $cleanData,
                    ['id' => (string) Str::uuid(), 'specific_objective_id' => $objective->id]
                ));
                $submittedResultIds[] = $result->id;
            }
            
            $objectiveIndex++;
        }

        $toDelete = array_diff($allExistingResultIds, $submittedResultIds);
        if (!empty($toDelete)) {
            Result::whereIn('id', $toDelete)->delete();
        }
    }

    private function syncActivities(LogicalFramework $logicalFramework, array $activitiesData): void
    {
        $allExistingActivityIds = [];
        $allResults = collect();
        foreach ($logicalFramework->specificObjectives as $objective) {
            foreach ($objective->results as $result) {
                $allResults->push($result);
                $activityIds = $result->activities->pluck('id')->toArray();
                $allExistingActivityIds = array_merge($allExistingActivityIds, $activityIds);
            }
        }

        $submittedActivityIds = [];
        $resultIndex = 0;

        foreach ($activitiesData as $activityData) {
            $cleanData = Arr::except($activityData, ['id', 'result_id', 'created_at', 'updated_at']);
            
            if (isset($cleanData['start_date'])) {
                $cleanData['start_date'] = Carbon::parse($cleanData['start_date'])->format('Y-m-d');
            }
            if (isset($cleanData['end_date'])) {
                $cleanData['end_date'] = Carbon::parse($cleanData['end_date'])->format('Y-m-d');
            }

            if ($allResults->isEmpty()) break;
            $result = $allResults[$resultIndex % count($allResults)];
            
            if (isset($activityData['id']) && in_array($activityData['id'], $allExistingActivityIds)) {
                Activity::where('id', $activityData['id'])->update($cleanData);
                $submittedActivityIds[] = $activityData['id'];
            } else {
                if (empty(trim($cleanData['description'] ?? ''))) continue;

                $activity = Activity::create(array_merge(
                    $cleanData,
                    ['id' => (string) Str::uuid(), 'result_id' => $result->id]
                ));
                $submittedActivityIds[] = $activity->id;
            }
            
            $resultIndex++;
        }

        $toDelete = array_diff($allExistingActivityIds, $submittedActivityIds);
        if (!empty($toDelete)) {
            Activity::whereIn('id', $toDelete)->delete();
        }
    }
}
