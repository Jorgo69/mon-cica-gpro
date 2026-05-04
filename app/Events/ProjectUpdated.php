<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Project $project,
        public string $action = 'updated',
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('org.' . $this->project->organization_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'project_id' => $this->project->id,
            'title' => $this->project->title,
            'status' => $this->project->status->value,
            'action' => $this->action,
        ];
    }

    public function broadcastWhen(): bool
    {
        return isBroadcastingEnabled();
    }
}
