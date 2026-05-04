<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Comment $comment,
        public string $projectId,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.' . $this->projectId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'comment_id' => $this->comment->id,
            'user_name' => $this->comment->user?->name ?? 'Unknown',
            'body' => $this->comment->body,
            'created_at' => $this->comment->created_at->toISOString(),
        ];
    }

    public function broadcastWhen(): bool
    {
        return isBroadcastingEnabled();
    }
}
