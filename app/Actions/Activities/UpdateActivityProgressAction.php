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

            // 3. Notifier
            $notification = new \App\Notifications\ActivityProgressUpdatedNotification($activity, $activity->progress_percentage);
            
            // On notifie le responsable s'il est différent de l'auteur de la mise à jour
            if ($activity->responsible && $activity->responsible->id !== auth()->id()) {
                $activity->responsible->notify($notification);
            }

            // On notifie le créateur du projet
            if ($activity->project && $activity->project->creator && $activity->project->creator->id !== auth()->id()) {
                $activity->project->creator->notify($notification);
            }

            return $activity;
        });
    }
}
