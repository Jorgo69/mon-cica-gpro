<?php

use App\Models\Project;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Private channels : authorization checked server-side.
| Presence channels : same + user info shared with other subscribers.
|
*/

// User's personal channel (notifications)
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return $user->id === $userId;
});

// Organization channel (project updates, budget alerts)
Broadcast::channel('org.{orgId}', function ($user, $orgId) {
    return $user->organization_id === $orgId;
});

// Project channel (comments, activity updates)
Broadcast::channel('project.{projectId}', function ($user, $projectId) {
    $project = Project::find($projectId);

    return $project && $project->organization_id === $user->organization_id;
});

// Presence: who's online in an organization
Broadcast::channel('presence.org.{orgId}', function ($user, $orgId) {
    if ($user->organization_id !== $orgId) {
        return null;
    }

    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
