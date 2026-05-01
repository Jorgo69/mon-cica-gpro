<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeadlineApproachingNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasFcmNotification;

    public function __construct(public $activity, public int $daysLeft)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->getNotificationChannels(NotificationType::DEADLINE_APPROACHING);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $projectTitle = $this->activity->project?->title ?? 'N/A';
        $label = $this->daysLeft === 1
            ? __('mail.deadline_approaching.label_tomorrow')
            : __('mail.deadline_approaching.label_days', ['days' => $this->daysLeft]);

        return (new MailMessage)
            ->subject(__('mail.deadline_approaching.subject', ['label' => $label, 'activity' => $this->activity->description]))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__('mail.deadline_approaching.line1', [
                'activity' => $this->activity->description,
                'project' => $projectTitle,
                'label' => $label,
            ]))
            ->line(__('mail.deadline_approaching.line2', ['date' => $this->activity->end_date->format('d/m/Y')]))
            ->action(__('mail.deadline_approaching.action'), route('project.show', $this->activity->project?->id))
            ->salutation(__('mail.salutation', ['app' => config('app.name')]));
    }

    public function toArray(object $notifiable): array
    {
        $label = $this->daysLeft === 1
            ? __('mail.deadline_approaching.label_tomorrow')
            : __('mail.deadline_approaching.label_days', ['days' => $this->daysLeft]);

        return [
            'activity_id' => $this->activity->id,
            'project_id' => $this->activity->project?->id,
            'title' => __('mail.deadline_approaching.title', ['label' => $label]),
            'message' => __('mail.deadline_approaching.message', ['activity' => $this->activity->description, 'label' => $label]),
            'action_url' => route('project.show', $this->activity->project?->id),
            'type' => NotificationType::DEADLINE_APPROACHING->value,
        ];
    }
}
