<?php

namespace App\Livewire\V1\Comments;

use App\Livewire\Traits\WithToastNotifications;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\CommentPostedNotification;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CommentSectionLivewire extends Component
{
    use WithToastNotifications;

    public string $commentableType;
    public string $commentableId;

    #[Validate('required|string|min:2|max:5000')]
    public string $body = '';

    public ?string $replyingTo = null;

    public function mount(string $commentableType, string $commentableId): void
    {
        $this->commentableType = $commentableType;
        $this->commentableId = $commentableId;
    }

    public function post(): void
    {
        $this->validate();

        $mentions = Comment::extractMentions($this->body);

        $comment = Comment::create([
            'commentable_type' => $this->commentableType,
            'commentable_id' => $this->commentableId,
            'parent_id' => $this->replyingTo,
            'body' => clean($this->body),
            'mentions' => $mentions ?: null,
        ]);

        $comment->load('user');

        $this->notifyParticipants($comment, $mentions);

        $this->body = '';
        $this->replyingTo = null;
        $this->notifyToast('success', 'Commentaire publie.');
    }

    public function reply(string $commentId): void
    {
        $this->replyingTo = $commentId;
    }

    public function cancelReply(): void
    {
        $this->replyingTo = null;
    }

    public function delete(string $commentId): void
    {
        $comment = Comment::find($commentId);

        if (!$comment) return;

        // Only author or org_admin can delete
        if ($comment->user_id !== auth()->id() && !auth()->user()->can('manage-users')) {
            $this->notifyToast('error', 'Non autorise.');
            return;
        }

        $comment->delete();
        $this->notifyToast('success', 'Commentaire supprime.');
    }

    public function render()
    {
        $comments = Comment::where('commentable_type', $this->commentableType)
            ->where('commentable_id', $this->commentableId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.v1.comments.comment-section-livewire', [
            'comments' => $comments,
        ]);
    }

    private function notifyParticipants(Comment $comment, array $mentions): void
    {
        $notifiedIds = [auth()->id()];

        // Notify mentioned users
        foreach ($mentions as $mention) {
            $user = User::find($mention['user_id']);
            if ($user && !in_array($user->id, $notifiedIds)) {
                $user->notify(new CommentPostedNotification($comment, isMention: true));
                $notifiedIds[] = $user->id;
            }
        }

        // Notify responsible user of the activity (if not author and not already notified)
        $commentable = $comment->commentable;
        if ($commentable && method_exists($commentable, 'responsibleUser')) {
            $responsible = $commentable->responsibleUser;
            if ($responsible && !in_array($responsible->id, $notifiedIds)) {
                $responsible->notify(new CommentPostedNotification($comment));
                $notifiedIds[] = $responsible->id;
            }
        }

        // If replying, notify parent comment author
        if ($comment->parent_id) {
            $parentAuthor = $comment->parent?->user;
            if ($parentAuthor && !in_array($parentAuthor->id, $notifiedIds)) {
                $parentAuthor->notify(new CommentPostedNotification($comment));
            }
        }
    }
}
