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
            ->subject("Projet \"{$this->project->title}\" — Statut modifié")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le statut du projet **{$this->project->title}** a été modifié.")
            ->line("**{$this->oldStatus}** → **{$this->newStatus}**")
            ->action('Voir le projet', route('project.show', $this->project->id))
            ->salutation('— ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'title' => 'Statut Projet Modifié',
            'message' => "Le projet \"{$this->project->title}\" est passé de {$this->oldStatus} à {$this->newStatus}.",
            'action_url' => route('project.show', $this->project->id),
        ];
    }
}
