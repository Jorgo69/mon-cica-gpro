<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasFcmNotification;

    public function __construct(public $project, public $oldStatus, public $newStatus)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->getNotificationChannels(NotificationType::PROJECT_STATUS_UPDATED);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('mail.project_status_updated.subject', ['project' => $this->project->title]))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__('mail.project_status_updated.line1', ['project' => $this->project->title]))
            ->line(__('mail.project_status_updated.line2', ['old_status' => $this->oldStatus, 'new_status' => $this->newStatus]))
            ->action(__('mail.project_status_updated.action'), route('project.show', $this->project->id))
            ->salutation(__('mail.salutation', ['app' => config('app.name')]));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'title' => __('mail.project_status_updated.title'),
            'message' => __('mail.project_status_updated.message', [
                'project' => $this->project->title,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
            ]),
            'action_url' => route('project.show', $this->project->id),
        ];
    }
}
