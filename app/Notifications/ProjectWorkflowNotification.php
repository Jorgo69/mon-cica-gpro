<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectWorkflowNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Project $project,
        protected User $actor,
        protected string $action,
        protected ?string $comment = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = __("workflow.mail.{$this->action}_subject", ['project' => $this->project->title]);

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line(__("workflow.mail.{$this->action}_line", [
                'actor' => $this->actor->name,
                'project' => $this->project->title,
            ]))
            ->action(__('workflow.mail.view_project'), route('projects.show', $this->project->id));

        if ($this->comment) {
            $message->line(__('workflow.mail.comment') . ' : ' . $this->comment);
        }

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'action' => $this->action,
            'comment' => $this->comment,
        ];
    }
}
