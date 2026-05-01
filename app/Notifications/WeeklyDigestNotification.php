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
            ->subject(__('mail.weekly_digest.subject', ['app' => config('app.name')]))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__('mail.weekly_digest.line_intro'));

        if ($this->digest['overdue_count'] > 0) {
            $mail->line(__('mail.weekly_digest.overdue', ['count' => $this->digest['overdue_count']]));
        }

        if ($this->digest['upcoming_count'] > 0) {
            $mail->line(__('mail.weekly_digest.upcoming', ['count' => $this->digest['upcoming_count']]));
        }

        if ($this->digest['completed_count'] > 0) {
            $mail->line(__('mail.weekly_digest.completed', ['count' => $this->digest['completed_count']]));
        }

        if ($this->digest['projects_count'] > 0) {
            $mail->line(__('mail.weekly_digest.projects', ['count' => $this->digest['projects_count']]));
        }

        return $mail
            ->action(__('mail.weekly_digest.action'), route('dashboard'))
            ->salutation(__('mail.salutation', ['app' => config('app.name')]));
    }
}
