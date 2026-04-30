<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BudgetThresholdNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $project, public float $usedPercent)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->getNotificationChannels(NotificationType::BUDGET_THRESHOLD);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $level = $this->usedPercent >= 100 ? 'depasse' : 'atteint ' . round($this->usedPercent) . '%';

        return (new MailMessage)
            ->subject("Budget {$level} — {$this->project->title}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le budget du projet **{$this->project->title}** a **{$level}**.")
            ->action('Voir le projet', route('project.show', $this->project->id))
            ->salutation('— ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        $level = $this->usedPercent >= 100 ? 'depasse' : 'atteint ' . round($this->usedPercent) . '%';

        return [
            'project_id' => $this->project->id,
            'title' => "Budget {$level}",
            'message' => "Le budget du projet \"{$this->project->title}\" a {$level}.",
            'action_url' => route('project.show', $this->project->id),
            'type' => NotificationType::BUDGET_THRESHOLD->value,
        ];
    }
}
