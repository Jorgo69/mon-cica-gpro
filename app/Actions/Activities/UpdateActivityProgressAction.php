<?php

namespace App\Actions\Activities;

use App\Models\Activity;
use App\Models\ProgressTracker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateActivityProgressAction
{
    public function execute(Activity $activity, array $data): Activity
    {
        // Block progress updates on non-operational projects
        $project = $activity->result?->specificObjective?->logicalFramework?->project;
        if ($project && !$project->status->isOperational()) {
            throw new \InvalidArgumentException(__('activities.project_not_operational'));
        }

        return DB::transaction(function () use ($activity, $data) {
            $activity->update([
                'status' => $data['status'] ?? $activity->status,
                'progress_percentage' => $data['progress_percentage'] ?? $activity->progress_percentage,
                'justification' => $data['justification'] ?? $activity->justification,
            ]);

            // Créer un enregistrement ProgressTracker (historique)
            ProgressTracker::create([
                'activity_id' => $activity->id,
                'project_id' => $activity->result?->specificObjective?->logicalFramework?->project_id,
                'organization_id' => $activity->organization_id,
                'creator_user_id' => Auth::id(),
                'date' => now()->toDateString(),
                'progress_percentage' => $activity->progress_percentage,
                'status_update' => $data['status'] ?? $activity->status,
                'justification' => $data['justification'] ?? null,
                'performance_score' => $data['performance_score'] ?? null,
                'evaluation_comment' => $data['evaluation_comment'] ?? null,
            ]);

            // Notifier
            $notification = new \App\Notifications\ActivityProgressUpdatedNotification($activity, $activity->progress_percentage);

            if ($activity->responsibleUser && $activity->responsibleUser->id !== Auth::id()) {
                $activity->responsibleUser->notify($notification);
            }

            if ($activity->project && $activity->project->creator && $activity->project->creator->id !== Auth::id()) {
                $activity->project->creator->notify($notification);
            }

            return $activity;
        });
    }
}
