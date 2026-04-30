<?php

namespace App\Actions;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\LogicalFramework;
use App\Models\SpecificObjective;
use App\Models\Result;
use App\Models\Activity;
use App\Models\User;
use App\Notifications\ActivityAssignedNotification;
use App\Traits\SyncsIndicators;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateLogicalFrameworkAction
{
    use SyncsIndicators;
    /**
     * Crée l'arbre complet du cadre logique pour un projet.
     * 
     * @param string $projectId
     * @param array $logicalFrameworkData
     * @param array $specificObjectivesData
     * @param array $expectedResultsData
     * @param array $activitiesData
     * @return LogicalFramework
     */
    public function execute(
        string $projectId, 
        array $logicalFrameworkData, 
        array $specificObjectivesData, 
        array $expectedResultsData, 
        array $activitiesData
    ): LogicalFramework {
        // Optionnel : Encapsuler dans une transaction si l'appelant ne le fait pas déjà
        return DB::transaction(function () use (
            $projectId, 
            $logicalFrameworkData, 
            $specificObjectivesData, 
            $expectedResultsData, 
            $activitiesData
        ) {
            
            // 🔹 1. Créer le cadre logique
            $indicatorsList = $logicalFrameworkData['indicators_list'] ?? [];
            $cleanLfData = \Illuminate\Support\Arr::except($logicalFrameworkData, ['indicators_list', 'specific_objectives']);
            $logicalFramework = LogicalFramework::create(array_merge(
                ['id' => (string) Str::orderedUuid(), 'project_id' => $projectId],
                $cleanLfData
            ));

            // Sync indicateurs du cadre logique (objectif general)
            if (!empty($indicatorsList)) {
                $this->syncIndicators($logicalFramework, $indicatorsList);
            }

            // 🔹 2. Créer les objectifs spécifiques
            $createdObjectives = [];
            foreach ($specificObjectivesData as $objData) {
                if (empty(trim($objData['description'] ?? ''))) continue;

                $objective = SpecificObjective::create([
                    'id'                   => (string) Str::orderedUuid(),
                    'logical_framework_id' => $logicalFramework->id,
                    'description'          => $objData['description'],
                    'indicators'           => $objData['indicators'] ?? null,
                    'verification_sources' => $objData['verification_sources'] ?? null,
                    'assumptions'          => $objData['assumptions'] ?? null,
                ]);

                if (!empty($objData['indicators_list'])) {
                    $this->syncIndicators($objective, $objData['indicators_list']);
                }

                $createdObjectives[] = $objective;
            }

            // Si aucun objectif n'est créé, créer un objectif par défaut pour lier les résultats
            if (empty($createdObjectives)) {
                 $createdObjectives[] = SpecificObjective::create([
                    'id'                   => (string) Str::orderedUuid(),
                    'logical_framework_id' => $logicalFramework->id,
                    'description'          => 'Objectif Principal',
                ]);
            }

            // 🔹 3. Créer les résultats attendus (distribués sur les objectifs)
            $createdResults = [];
            foreach ($expectedResultsData as $index => $resData) {
                if (empty(trim($resData['description'] ?? ''))) continue;

                $objectiveIndex = $index % count($createdObjectives);
                $objective = $createdObjectives[$objectiveIndex];

                $result = Result::create([
                    'id'                   => (string) Str::orderedUuid(),
                    'specific_objective_id'=> $objective->id,
                    'description'          => $resData['description'],
                ]);

                if (!empty($resData['indicators_list'])) {
                    $this->syncIndicators($result, $resData['indicators_list']);
                }

                $createdResults[] = $result;
            }

            // Si aucun résultat n'est créé, créer un résultat par défaut pour lier les activités
            if (empty($createdResults)) {
                $createdResults[] = Result::create([
                    'id'                   => (string) Str::orderedUuid(),
                    'specific_objective_id'=> $createdObjectives[0]->id,
                    'description'          => 'Résultat Principal',
                ]);
            }

            // 🔹 4. Créer les activités (distribuées sur les résultats)
            foreach ($activitiesData as $index => $actData) {
                if (empty(trim($actData['description'] ?? ''))) continue;

                $resultIndex = $index % count($createdResults);
                $result = $createdResults[$resultIndex];

                $activity = Activity::create([
                    'id'        => (string) Str::orderedUuid(),
                    'result_id' => $result->id,
                    'description'=> $actData['description'],
                    'responsible_user_id'=> $actData['responsible_user_id'] ?? null,
                    'budget'=> $actData['budget'] ?? null,
                    'is_milestone'=> $actData['is_milestone'] ?? false,
                    'start_date'=> !empty($actData['start_date']) ? Carbon::parse($actData['start_date'])->format('Y-m-d') : null,
                    'end_date'  => !empty($actData['end_date']) ? Carbon::parse($actData['end_date'])->format('Y-m-d') : null,
                ]);

                // Notifier le responsable assigné
                $responsibleId = $actData['responsible_user_id'] ?? null;
                if ($responsibleId && $responsibleId !== Auth::id()) {
                    $responsible = User::find($responsibleId);
                    if ($responsible) {
                        $responsible->notify(new ActivityAssignedNotification($activity, Auth::user()));
                    }
                }
            }

            return $logicalFramework;
        });
    }
}
