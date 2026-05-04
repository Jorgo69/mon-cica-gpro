<?php

namespace App\Events;

use App\Models\Activity;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Activity $activity,
        public string $action = 'updated',
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('org.' . $this->activity->organization_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'activity_id' => $this->activity->id,
            'description' => $this->activity->description,
            'progress' => $this->activity->progress_percentage,
            'status' => $this->activity->status->value,
            'action' => $this->action,
        ];
    }

    public function broadcastWhen(): bool
    {
        return isBroadcastingEnabled();
    }
}
