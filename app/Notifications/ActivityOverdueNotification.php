<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasFcmNotification;

    public function __construct(public $activity, public int $daysOverdue, public bool $isEscalation = false)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->getNotificationChannels(NotificationType::ACTIVITY_OVERDUE);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $projectTitle = $this->activity->project?->title ?? 'N/A';
        $subject = $this->isEscalation
            ? __('mail.activity_overdue.subject_escalation', ['days' => $this->daysOverdue])
            : __('mail.activity_overdue.subject_normal', ['activity' => $this->activity->description]);

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]));

        if ($this->isEscalation) {
            $mail->line(__('mail.activity_overdue.line_escalation1', [
                    'activity' => $this->activity->description,
                    'project' => $projectTitle,
                    'days' => $this->daysOverdue,
                ]))
                ->line(__('mail.activity_overdue.line_escalation2', [
                    'responsible' => $this->activity->responsibleUser?->name,
                ]));
        } else {
            $mail->line(__('mail.activity_overdue.line_normal1', [
                    'activity' => $this->activity->description,
                    'project' => $projectTitle,
                    'days' => $this->daysOverdue,
                ]))
                ->line(__('mail.activity_overdue.line_normal2', [
                    'date' => $this->activity->end_date->format('d/m/Y'),
                ]));
        }

        return $mail
            ->action(__('mail.activity_overdue.action'), route('project.show', $this->activity->project?->id))
            ->salutation(__('mail.salutation', ['app' => config('app.name')]));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'activity_id' => $this->activity->id,
            'project_id' => $this->activity->project?->id,
            'title' => $this->isEscalation
                ? __('mail.activity_overdue.title_escalation', ['days' => $this->daysOverdue])
                : __('mail.activity_overdue.title_normal', ['days' => $this->daysOverdue]),
            'message' => $this->isEscalation
                ? __('mail.activity_overdue.message_escalation', ['activity' => $this->activity->description, 'days' => $this->daysOverdue])
                : __('mail.activity_overdue.message_normal', ['activity' => $this->activity->description, 'days' => $this->daysOverdue]),
            'action_url' => route('project.show', $this->activity->project?->id),
            'type' => NotificationType::ACTIVITY_OVERDUE->value,
        ];
    }
}
