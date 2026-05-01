<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityProgressUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasFcmNotification;

    public function __construct(public $activity, public $progress)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->getNotificationChannels(NotificationType::ACTIVITY_PROGRESS_UPDATED);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $projectTitle = $this->activity->project?->title ?? 'N/A';

        return (new MailMessage)
            ->subject(__('mail.activity_progress_updated.subject', ['progress' => $this->progress]))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__('mail.activity_progress_updated.line1', [
                'activity' => $this->activity->description,
                'project' => $projectTitle,
                'progress' => $this->progress,
            ]))
            ->action(__('mail.activity_progress_updated.action'), route('project.show', $this->activity->project?->id))
            ->salutation(__('mail.salutation', ['app' => config('app.name')]));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'activity_id' => $this->activity->id,
            'project_id' => $this->activity->project?->id,
            'title' => __('mail.activity_progress_updated.title'),
            'message' => __('mail.activity_progress_updated.message', [
                'activity' => $this->activity->description,
                'progress' => $this->progress,
            ]),
            'action_url' => route('project.show', $this->activity->project?->id),
        ];
    }
}
