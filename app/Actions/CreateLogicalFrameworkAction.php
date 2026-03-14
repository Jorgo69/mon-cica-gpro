<?php

namespace App\Actions;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\LogicalFramework;
use App\Models\SpecificObjective;
use App\Models\Result;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class CreateLogicalFrameworkAction
{
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
            $logicalFramework = LogicalFramework::create(array_merge(
                ['id' => (string) Str::uuid(), 'project_id' => $projectId],
                $logicalFrameworkData
            ));

            // 🔹 2. Créer les objectifs spécifiques
            $createdObjectives = [];
            foreach ($specificObjectivesData as $objData) {
                if (empty(trim($objData['description'] ?? ''))) continue;

                $createdObjectives[] = SpecificObjective::create([
                    'id'                   => (string) Str::uuid(),
                    'logical_framework_id' => $logicalFramework->id,
                    'description'          => $objData['description'],
                    'indicators'           => $objData['indicators'] ?? null,
                    'verification_sources' => $objData['verification_sources'] ?? null,
                    'assumptions'          => $objData['assumptions'] ?? null,
                ]);
            }

            // Si aucun objectif n'est créé, créer un objectif par défaut pour lier les résultats
            if (empty($createdObjectives)) {
                 $createdObjectives[] = SpecificObjective::create([
                    'id'                   => (string) Str::uuid(),
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

                $createdResults[] = Result::create([
                    'id'                   => (string) Str::uuid(),
                    'specific_objective_id'=> $objective->id,
                    'description'          => $resData['description'],
                ]);
            }

            // Si aucun résultat n'est créé, créer un résultat par défaut pour lier les activités
            if (empty($createdResults)) {
                $createdResults[] = Result::create([
                    'id'                   => (string) Str::uuid(),
                    'specific_objective_id'=> $createdObjectives[0]->id,
                    'description'          => 'Résultat Principal',
                ]);
            }

            // 🔹 4. Créer les activités (distribuées sur les résultats)
            foreach ($activitiesData as $index => $actData) {
                if (empty(trim($actData['description'] ?? ''))) continue;

                $resultIndex = $index % count($createdResults);
                $result = $createdResults[$resultIndex];

                Activity::create([
                    'id'        => (string) Str::uuid(),
                    'result_id' => $result->id,
                    'description'=> $actData['description'],
                    'responsible_user_id'=> $actData['responsible_user_id'] ?? null,
                    'budget'=> $actData['budget'] ?? null,
                    'is_milestone'=> $actData['is_milestone'] ?? false,
                    'start_date'=> !empty($actData['start_date']) ? Carbon::parse($actData['start_date'])->format('Y-m-d') : null,
                    'end_date'  => !empty($actData['end_date']) ? Carbon::parse($actData['end_date'])->format('Y-m-d') : null,
                ]);
            }

            return $logicalFramework;
        });
    }
}
