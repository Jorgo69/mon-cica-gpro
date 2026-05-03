<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasFcmNotification;

    public function __construct(public $project, public $submitter)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->getNotificationChannels(NotificationType::PROJECT_SUBMITTED);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('mail.project_submitted.subject', ['project' => $this->project->title]))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__('mail.project_submitted.line1', ['submitter' => $this->submitter->name, 'project' => $this->project->title]))
            ->line(__('mail.project_submitted.line2', ['code' => $this->project->project_code]))
            ->action(__('mail.project_submitted.action'), route('project.show', $this->project->id))
            ->salutation(__('mail.salutation', ['app' => config('app.name')]));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'title' => __('mail.project_submitted.title'),
            'message' => __('mail.project_submitted.message', [
                'submitter' => $this->submitter->name,
                'project' => $this->project->title,
            ]),
            'action_url' => route('project.show', $this->project->id),
        ];
    }
}
