<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectReportNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Project $project,
        protected string $format = 'pdf',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('reports.mail.subject', ['project' => $this->project->title]))
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__('reports.mail.line', [
                'project' => $this->project->title,
                'format' => strtoupper($this->format),
            ]))
            ->action(__('reports.mail.view_project'), route('projects.show', $this->project->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'format' => $this->format,
            'type' => 'report_generated',
        ];
    }
}
