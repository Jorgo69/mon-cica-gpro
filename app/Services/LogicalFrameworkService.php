<?php

namespace App\Services;

use App\Models\LogicalFramework;
use App\Models\SpecificObjective;
use App\Models\Result;
use App\Models\Activity;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogicalFrameworkService
{
    /**
     * Synchronize activities for a logical framework.
     * Fixes the duplication bug by using proper IDs and updateOrCreate.
     */
    public function syncActivities(LogicalFramework $logicalFramework, array $activitiesData): void
    {
        DB::transaction(function () use ($logicalFramework, $activitiesData) {
            // Map results for quick access
            $results = $logicalFramework->specificObjectives->flatMap->results;
            
            if ($results->isEmpty()) {
                throw new \Exception("Le cadre logique doit avoir au moins un résultat pour ajouter des activités.");
            }

            $submittedIds = [];

            foreach ($activitiesData as $index => $data) {
                // Ensure we have a result_id. If not, auto-assign based on index (legacy behavior but safer)
                $resultId = $data['result_id'] ?? $results[$index % $results->count()]->id;

                $activity = Activity::updateOrCreate(
                    ['id' => $data['id'] ?? (string) Str::orderedUuid()],
                    [
                        'result_id' => $resultId,
                        'title' => $data['title'],
                        'description' => $data['description'] ?? null,
                        'start_date' => isset($data['start_date']) ? Carbon::parse($data['start_date']) : null,
                        'end_date' => isset($data['end_date']) ? Carbon::parse($data['end_date']) : null,
                        'status' => $data['status'] ?? 'En Attente',
                        'progress_percentage' => $data['progress_percentage'] ?? 0,
                    ]
                );

                $submittedIds[] = $activity->id;
            }

            // Cleanup: Delete activities not in the submitted list for this framework
            $logicalFramework->specificObjectives->each(function ($objective) use ($submittedIds) {
                $objective->results->each(function ($result) use ($submittedIds) {
                    $result->activities()->whereNotIn('id', $submittedIds)->delete();
                });
            });
        });
    }
}
