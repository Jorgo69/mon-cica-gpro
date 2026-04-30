<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $digest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Resume hebdomadaire — ' . config('app.name'))
            ->greeting("Bonjour {$notifiable->name},")
            ->line('Voici le resume de la semaine :');

        if ($this->digest['overdue_count'] > 0) {
            $mail->line("**{$this->digest['overdue_count']}** activite(s) en retard");
        }

        if ($this->digest['upcoming_count'] > 0) {
            $mail->line("**{$this->digest['upcoming_count']}** echeance(s) cette semaine");
        }

        if ($this->digest['completed_count'] > 0) {
            $mail->line("**{$this->digest['completed_count']}** activite(s) terminees cette semaine");
        }

        if ($this->digest['projects_count'] > 0) {
            $mail->line("**{$this->digest['projects_count']}** projet(s) actifs");
        }

        return $mail
            ->action('Voir le tableau de bord', route('dashboard'))
            ->salutation('— ' . config('app.name'));
    }
}
