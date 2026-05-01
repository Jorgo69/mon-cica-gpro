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
        $author = $this->comment->user->name ?? __('mail.someone');
        $subject = $this->isMention
            ? __('mail.comment_posted.subject_mention', ['author' => $author])
            : __('mail.comment_posted.subject_comment', ['author' => $author]);

        $url = $this->getActionUrl();

        return (new MailMessage)
            ->subject($subject)
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line($subject . '.')
            ->line('> ' . \Illuminate\Support\Str::limit(strip_tags($this->comment->body), 200))
            ->action(__('mail.comment_posted.action'), $url)
            ->salutation(__('mail.salutation', ['app' => config('app.name')]));
    }

    public function toArray(object $notifiable): array
    {
        $author = $this->comment->user->name ?? __('mail.someone');
        $excerpt = \Illuminate\Support\Str::limit(strip_tags($this->comment->body), 100);

        return [
            'comment_id' => $this->comment->id,
            'title' => $this->isMention
                ? __('mail.comment_posted.title_mention')
                : __('mail.comment_posted.title_comment'),
            'message' => $this->isMention
                ? __('mail.comment_posted.message_mention', ['author' => $author, 'excerpt' => $excerpt])
                : __('mail.comment_posted.message_comment', ['author' => $author, 'excerpt' => $excerpt]),
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
