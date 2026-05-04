<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Project;
use App\Services\WebhookDispatcher;

class WebhookObserver
{
    public static function bootProjectEvents(): void
    {
        Project::created(function (Project $project) {
            if ($project->organization_id) {
                WebhookDispatcher::dispatch($project->organization_id, 'project.created', [
                    'project_id' => $project->id,
                    'title' => $project->title,
                    'status' => $project->status?->value,
                ]);
            }
        });

        Project::updated(function (Project $project) {
            if (!$project->organization_id) return;

            if ($project->wasChanged('status')) {
                WebhookDispatcher::dispatch($project->organization_id, 'project.status_changed', [
                    'project_id' => $project->id,
                    'title' => $project->title,
                    'from' => $project->getOriginal('status'),
                    'to' => $project->status?->value,
                ]);
            } else {
                WebhookDispatcher::dispatch($project->organization_id, 'project.updated', [
                    'project_id' => $project->id,
                    'title' => $project->title,
                    'changed' => array_keys($project->getChanges()),
                ]);
            }
        });

        Project::deleted(function (Project $project) {
            if ($project->organization_id) {
                WebhookDispatcher::dispatch($project->organization_id, 'project.deleted', [
                    'project_id' => $project->id,
                    'title' => $project->title,
                ]);
            }
        });
    }

    public static function bootActivityEvents(): void
    {
        Activity::created(function (Activity $activity) {
            if ($activity->organization_id) {
                WebhookDispatcher::dispatch($activity->organization_id, 'activity.created', [
                    'activity_id' => $activity->id,
                    'description' => $activity->description,
                ]);
            }
        });

        Activity::updated(function (Activity $activity) {
            if (!$activity->organization_id) return;

            if ($activity->wasChanged('status') && $activity->status === \App\Enums\ActivityStatus::COMPLETED) {
                WebhookDispatcher::dispatch($activity->organization_id, 'activity.completed', [
                    'activity_id' => $activity->id,
                    'description' => $activity->description,
                ]);
            }
        });
    }
}
