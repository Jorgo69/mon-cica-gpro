<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BudgetAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $orgId,
        public string $projectId,
        public string $projectTitle,
        public float $usedPercent,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('org.' . $this->orgId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'project_id' => $this->projectId,
            'project_title' => $this->projectTitle,
            'used_percent' => $this->usedPercent,
        ];
    }

    public function broadcastWhen(): bool
    {
        return isBroadcastingEnabled();
    }
}
