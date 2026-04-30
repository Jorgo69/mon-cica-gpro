<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentPostedNotification extends Notification implements ShouldQueue
{
    use Queueable, \App\Traits\HasFcmNotification;

    public function __construct(
        public Comment $comment,
        public bool $isMention = false,
    ) {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->getNotificationChannels(NotificationType::ACTIVITY_PROGRESS_UPDATED);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $author = $this->comment->user->name ?? 'Quelqu\'un';
        $subject = $this->isMention
            ? "{$author} vous a mentionne dans un commentaire"
            : "{$author} a commente une activite";

        $url = $this->getActionUrl();

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Bonjour {$notifiable->name},")
            ->line($subject . '.')
            ->line('> ' . \Illuminate\Support\Str::limit(strip_tags($this->comment->body), 200))
            ->action('Voir', $url)
            ->salutation('— ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        $author = $this->comment->user->name ?? 'Quelqu\'un';

        return [
            'comment_id' => $this->comment->id,
            'title' => $this->isMention ? 'Vous avez ete mentionne' : 'Nouveau commentaire',
            'message' => $this->isMention
                ? "{$author} vous a mentionne : \"" . \Illuminate\Support\Str::limit(strip_tags($this->comment->body), 100) . '"'
                : "{$author} a commente : \"" . \Illuminate\Support\Str::limit(strip_tags($this->comment->body), 100) . '"',
            'action_url' => $this->getActionUrl(),
        ];
    }

    private function getActionUrl(): string
    {
        $commentable = $this->comment->commentable;

        if ($commentable instanceof \App\Models\Activity) {
            $project = $commentable->project;
            return $project ? route('project.show', $project->id) : route('dashboard');
        }

        if ($commentable instanceof \App\Models\Project) {
            return route('project.show', $commentable->id);
        }

        return route('dashboard');
    }
}
