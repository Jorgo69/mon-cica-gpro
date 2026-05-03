<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BudgetThresholdNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasFcmNotification;

    public function __construct(public $project, public float $usedPercent)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->getNotificationChannels(NotificationType::BUDGET_THRESHOLD);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $level = $this->usedPercent >= 100
            ? __('mail.budget_threshold.level_exceeded')
            : __('mail.budget_threshold.level_reached', ['percent' => round($this->usedPercent)]);

        return (new MailMessage)
            ->subject(__('mail.budget_threshold.subject', ['level' => $level, 'project' => $this->project->title]))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__('mail.budget_threshold.line1', ['project' => $this->project->title, 'level' => $level]))
            ->action(__('mail.budget_threshold.action'), route('project.show', $this->project->id))
            ->salutation(__('mail.salutation', ['app' => config('app.name')]));
    }

    public function toArray(object $notifiable): array
    {
        $level = $this->usedPercent >= 100
            ? __('mail.budget_threshold.level_exceeded')
            : __('mail.budget_threshold.level_reached', ['percent' => round($this->usedPercent)]);

        return [
            'project_id' => $this->project->id,
            'title' => __('mail.budget_threshold.title', ['level' => $level]),
            'message' => __('mail.budget_threshold.message', ['project' => $this->project->title, 'level' => $level]),
            'action_url' => route('project.show', $this->project->id),
            'type' => NotificationType::BUDGET_THRESHOLD->value,
        ];
    }
}
