<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public $project, public $submitter)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nouveau projet soumis : {$this->project->title}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("**{$this->submitter->name}** a soumis le projet **{$this->project->title}** pour validation.")
            ->line("Code projet : {$this->project->project_code}")
            ->action('Voir le projet', route('project.show', $this->project->id))
            ->salutation('— ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'title' => 'Nouveau projet soumis',
            'message' => "{$this->submitter->name} a soumis le projet \"{$this->project->title}\" pour validation.",
            'action_url' => route('project.show', $this->project->id),
        ];
    }
}
