<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\ProjectApproval;
use App\Models\User;
use App\Notifications\ProjectWorkflowNotification;
use App\Traits\DispatchesBroadcastEvents;
use Illuminate\Support\Facades\DB;

class WorkflowService
{
    use DispatchesBroadcastEvents;
    public function transition(Project $project, User $actor, ProjectStatus $targetStatus, ?string $comment = null): ProjectApproval
    {
        $currentStatus = $project->status;

        if (!$currentStatus->canTransitionTo($targetStatus)) {
            throw new \InvalidArgumentException(
                __('workflow.invalid_transition', [
                    'from' => $currentStatus->label(),
                    'to' => $targetStatus->label(),
                ])
            );
        }

        $action = $this->resolveAction($currentStatus, $targetStatus);
        $this->authorizeAction($actor, $project, $action);

        return DB::transaction(function () use ($project, $actor, $currentStatus, $targetStatus, $action, $comment) {
            $project->update(['status' => $targetStatus]);

            $approval = ProjectApproval::create([
                'project_id' => $project->id,
                'user_id' => $actor->id,
                'action' => $action,
                'from_status' => $currentStatus->value,
                'to_status' => $targetStatus->value,
                'comment' => $comment,
            ]);

            $this->notifyStakeholders($project, $actor, $action, $comment);
            $this->broadcastProjectUpdated($project, $action);

            return $approval;
        });
    }

    public function submit(Project $project, User $actor, ?string $comment = null): ProjectApproval
    {
        return $this->transition($project, $actor, ProjectStatus::SUBMITTED, $comment);
    }

    public function startReview(Project $project, User $actor, ?string $comment = null): ProjectApproval
    {
        return $this->transition($project, $actor, ProjectStatus::UNDER_REVIEW, $comment);
    }

    public function approve(Project $project, User $actor, ?string $comment = null): ProjectApproval
    {
        return $this->transition($project, $actor, ProjectStatus::APPROVED, $comment);
    }

    public function reject(Project $project, User $actor, ?string $comment = null): ProjectApproval
    {
        return $this->transition($project, $actor, ProjectStatus::REJECTED, $comment);
    }

    public function activate(Project $project, User $actor, ?string $comment = null): ProjectApproval
    {
        return $this->transition($project, $actor, ProjectStatus::ACTIVE, $comment);
    }

    public function revertToDraft(Project $project, User $actor, ?string $comment = null): ProjectApproval
    {
        return $this->transition($project, $actor, ProjectStatus::DRAFT, $comment);
    }

    protected function resolveAction(ProjectStatus $from, ProjectStatus $to): string
    {
        return match (true) {
            $to === ProjectStatus::SUBMITTED => 'submit',
            $to === ProjectStatus::UNDER_REVIEW => 'review',
            $to === ProjectStatus::APPROVED => 'approve',
            $to === ProjectStatus::REJECTED => 'reject',
            $to === ProjectStatus::ACTIVE && $from === ProjectStatus::APPROVED => 'activate',
            $to === ProjectStatus::DRAFT => 'revert_to_draft',
            default => 'transition',
        };
    }

    protected function authorizeAction(User $actor, Project $project, string $action): void
    {
        $isAdmin = $actor->role === AccountType::ORG_ADMIN || $actor->role === AccountType::ROOT;
        $isCreator = $actor->id === $project->creator_user_id;
        $isManager = $actor->hasPermissionTo('edit-projects');

        match ($action) {
            'submit' => $isCreator || $isAdmin || throw new \InvalidArgumentException(__('workflow.only_creator_can_submit')),
            'review', 'approve', 'reject' => $isManager || $isAdmin || throw new \InvalidArgumentException(__('workflow.only_manager_can_review')),
            'activate' => $isAdmin || throw new \InvalidArgumentException(__('workflow.only_admin_can_activate')),
            'revert_to_draft' => $isCreator || $isAdmin || throw new \InvalidArgumentException(__('workflow.unauthorized')),
            default => $isAdmin || throw new \InvalidArgumentException(__('workflow.unauthorized')),
        };
    }

    protected function notifyStakeholders(Project $project, User $actor, string $action, ?string $comment): void
    {
        $recipients = collect();

        // Notify creator (if not the actor)
        if ($project->creator_user_id && $project->creator_user_id !== $actor->id) {
            $recipients->push($project->creator);
        }

        // Notify org admins for submissions
        if (in_array($action, ['submit'])) {
            $admins = User::where('organization_id', $project->organization_id)
                ->where('role', AccountType::ORG_ADMIN)
                ->where('id', '!=', $actor->id)
                ->get();
            $recipients = $recipients->merge($admins);
        }

        // Notify project members
        $members = $project->members()
            ->where('user_id', '!=', $actor->id)
            ->get();
        $recipients = $recipients->merge($members);

        $recipients->unique('id')->each(function ($user) use ($project, $actor, $action, $comment) {
            $user->notify(new ProjectWorkflowNotification($project, $actor, $action, $comment));
        });
    }
}
