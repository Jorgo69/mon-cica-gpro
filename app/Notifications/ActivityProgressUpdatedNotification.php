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
            ->subject("Activité mise à jour — {$this->progress}%")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("L'activité **{$this->activity->description}** du projet **{$projectTitle}** est passée à **{$this->progress}%** de réalisation.")
            ->action('Voir le projet', route('project.show', $this->activity->project?->id))
            ->salutation('— ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'activity_id' => $this->activity->id,
            'project_id' => $this->activity->project?->id,
            'title' => 'Progression Activité Mise à jour',
            'message' => "L'activité \"{$this->activity->description}\" est passée à {$this->progress}% de réalisation.",
            'action_url' => route('project.show', $this->activity->project?->id),
        ];
    }
}
