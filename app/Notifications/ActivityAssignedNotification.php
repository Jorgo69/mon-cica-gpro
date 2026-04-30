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
            ->subject("Activité assignée : {$this->activity->description}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("**{$this->assigner->name}** vous a assigné une activité sur le projet **{$projectTitle}**.")
            ->line("Activité : **{$this->activity->description}**")
            ->action('Voir le projet', route('project.show', $this->activity->project?->id))
            ->salutation('— ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'activity_id' => $this->activity->id,
            'project_id' => $this->activity->project?->id,
            'title' => 'Activité assignée',
            'message' => "{$this->assigner->name} vous a assigné l'activité \"{$this->activity->description}\".",
            'action_url' => route('project.show', $this->activity->project?->id),
        ];
    }
}
