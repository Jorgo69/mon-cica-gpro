<?php

namespace App\Actions\Activities;

use App\Models\Activity;
use App\Models\ProgressTracker;
use Illuminate\Support\Facades\DB;

class UpdateActivityProgressAction
{
    /**
     * Execute the action to update activity progress and log it.
     *
     * @param Activity $activity
     * @param array $data
     * @return Activity
     */
    public function execute(Activity $activity, array $data): Activity
    {
        return DB::transaction(function () use ($activity, $data) {
            // 1. Mettre à jour l'activité
            $activity->update([
                'status' => $data['status'] ?? $activity->status,
                'progress_percentage' => $data['progress_percentage'] ?? $activity->progress_percentage,
                'justification' => $data['justification'] ?? $activity->justification,
            ]);

            // 2. Créer une entrée dans le tracker de progression pour l'historique
            ProgressTracker::create([
                'activity_id' => $activity->id,
                'project_id' => $activity->project?->id,
                'date' => now(),
                'progress_percentage' => $activity->progress_percentage,
                'status_update' => "Mise à jour via action: " . ($data['status'] ?? $activity->status->value),
                'justification' => $data['justification'] ?? null,
            ]);

            return $activity;
        });
    }
}
