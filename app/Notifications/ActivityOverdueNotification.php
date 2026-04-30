<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
            ? "[ESCALADE] Activite en retard depuis {$this->daysOverdue} jours"
            : "Activite en retard : {$this->activity->description}";

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Bonjour {$notifiable->name},");

        if ($this->isEscalation) {
            $mail->line("**ESCALADE** — L'activite **{$this->activity->description}** du projet **{$projectTitle}** est en retard depuis **{$this->daysOverdue} jours**.")
                ->line("Le responsable ({$this->activity->responsibleUser?->name}) n'a pas mis a jour cette activite. Votre intervention est requise.");
        } else {
            $mail->line("L'activite **{$this->activity->description}** du projet **{$projectTitle}** est en retard de **{$this->daysOverdue} jour(s)**.")
                ->line("Date limite depassee : **{$this->activity->end_date->format('d/m/Y')}**");
        }

        return $mail
            ->action('Voir le projet', route('project.show', $this->activity->project?->id))
            ->salutation('— ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        $prefix = $this->isEscalation ? '[ESCALADE] ' : '';

        return [
            'activity_id' => $this->activity->id,
            'project_id' => $this->activity->project?->id,
            'title' => "{$prefix}Activite en retard ({$this->daysOverdue}j)",
            'message' => "{$prefix}L'activite \"{$this->activity->description}\" est en retard de {$this->daysOverdue} jour(s).",
            'action_url' => route('project.show', $this->activity->project?->id),
            'type' => NotificationType::ACTIVITY_OVERDUE->value,
        ];
    }
}
