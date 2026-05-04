<?php

namespace App\Traits;

use App\Events\ActivityUpdated;
use App\Events\BudgetAlert;
use App\Events\CommentPosted;
use App\Events\NewNotification;
use App\Events\ProjectUpdated;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Project;

trait DispatchesBroadcastEvents
{
    protected function broadcastProjectUpdated(Project $project, string $action = 'updated'): void
    {
        if (isBroadcastingEnabled()) {
            event(new ProjectUpdated($project, $action));
        }
    }

    protected function broadcastActivityUpdated(Activity $activity, string $action = 'updated'): void
    {
        if (isBroadcastingEnabled()) {
            event(new ActivityUpdated($activity, $action));
        }
    }

    protected function broadcastNewNotification(string $userId, string $type, string $message): void
    {
        if (isBroadcastingEnabled()) {
            event(new NewNotification($userId, $type, $message));
        }
    }

    protected function broadcastCommentPosted(Comment $comment, string $projectId): void
    {
        if (isBroadcastingEnabled()) {
            event(new CommentPosted($comment, $projectId));
        }
    }

    protected function broadcastBudgetAlert(string $orgId, string $projectId, string $projectTitle, float $usedPercent): void
    {
        if (isBroadcastingEnabled()) {
            event(new BudgetAlert($orgId, $projectId, $projectTitle, $usedPercent));
        }
    }
}
