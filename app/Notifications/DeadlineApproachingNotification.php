<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeadlineApproachingNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
        $label = $this->daysLeft === 1 ? 'demain' : "dans {$this->daysLeft} jours";

        return (new MailMessage)
            ->subject("Echeance {$label} : {$this->activity->description}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("L'activite **{$this->activity->description}** du projet **{$projectTitle}** arrive a echeance **{$label}**.")
            ->line("Date limite : **{$this->activity->end_date->format('d/m/Y')}**")
            ->action('Voir le projet', route('project.show', $this->activity->project?->id))
            ->salutation('— ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        $label = $this->daysLeft === 1 ? 'demain' : "dans {$this->daysLeft} jours";

        return [
            'activity_id' => $this->activity->id,
            'project_id' => $this->activity->project?->id,
            'title' => "Echeance {$label}",
            'message' => "L'activite \"{$this->activity->description}\" arrive a echeance {$label}.",
            'action_url' => route('project.show', $this->activity->project?->id),
            'type' => NotificationType::DEADLINE_APPROACHING->value,
        ];
    }
}
