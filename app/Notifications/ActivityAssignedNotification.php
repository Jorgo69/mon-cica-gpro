<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasFcmNotification;

    public function __construct(public $activity, public $assigner)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->getNotificationChannels(NotificationType::ACTIVITY_ASSIGNED);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $projectTitle = $this->activity->project?->title ?? 'N/A';

        return (new MailMessage)
            ->subject(__('mail.activity_assigned.subject', ['activity' => $this->activity->description]))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__('mail.activity_assigned.line1', ['assigner' => $this->assigner->name, 'project' => $projectTitle]))
            ->line(__('mail.activity_assigned.line2', ['activity' => $this->activity->description]))
            ->action(__('mail.activity_assigned.action'), route('project.show', $this->activity->project?->id))
            ->salutation(__('mail.salutation', ['app' => config('app.name')]));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'activity_id' => $this->activity->id,
            'project_id' => $this->activity->project?->id,
            'title' => __('mail.activity_assigned.title'),
            'message' => __('mail.activity_assigned.message', [
                'assigner' => $this->assigner->name,
                'activity' => $this->activity->description,
            ]),
            'action_url' => route('project.show', $this->activity->project?->id),
        ];
    }
}
