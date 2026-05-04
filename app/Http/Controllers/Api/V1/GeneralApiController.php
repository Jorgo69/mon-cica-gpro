<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AuditLogResource;
use App\Http\Resources\Api\MemberResource;
use App\Http\Resources\Api\NotificationResource;
use App\Models\Activity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity as AuditLog;

class GeneralApiController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('organization:id,name,slug');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role?->value,
            'organization' => $user->organization ? [
                'id' => $user->organization->id,
                'name' => $user->organization->name,
            ] : null,
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    public function stats(Request $request)
    {
        $user = $request->user();
        $orgId = $user->organization_id;

        $projectQuery = Project::query();
        $activityQuery = Activity::query();

        if ($orgId) {
            $projectQuery->where('organization_id', $orgId);
            $activityQuery->where('organization_id', $orgId);
        } else {
            $projectQuery->where('creator_user_id', $user->id);
            $activityQuery->where('creator_user_id', $user->id);
        }

        $projects = $projectQuery->get();
        $activities = $activityQuery->get();

        return response()->json([
            'projects' => [
                'total' => $projects->count(),
                'active' => $projects->where('status', \App\Enums\ProjectStatus::ACTIVE)->count(),
                'completed' => $projects->where('status', \App\Enums\ProjectStatus::COMPLETED)->count(),
                'draft' => $projects->where('status', \App\Enums\ProjectStatus::DRAFT)->count(),
            ],
            'activities' => [
                'total' => $activities->count(),
                'completed' => $activities->where('status', \App\Enums\ActivityStatus::COMPLETED)->count(),
                'ongoing' => $activities->where('status', \App\Enums\ActivityStatus::ONGOING)->count(),
                'overdue' => $activities->where('status', \App\Enums\ActivityStatus::OVERDUE)->count(),
            ],
            'budget' => [
                'planned' => $orgId
                    ? \App\Models\Budget::whereHas('project', fn ($q) => $q->where('organization_id', $orgId))->sum('total_cost')
                    : 0,
                'spent' => $orgId
                    ? \App\Models\Expense::whereHas('project', fn ($q) => $q->where('organization_id', $orgId))->sum('amount')
                    : 0,
            ],
            'members' => $orgId ? User::where('organization_id', $orgId)->count() : 1,
        ]);
    }

    public function members(Request $request)
    {
        $user = $request->user();

        if (!$user->organization_id) {
            return MemberResource::collection(collect([$user]));
        }

        $members = User::where('organization_id', $user->organization_id)
            ->orderBy('name')
            ->paginate($request->integer('per_page', 20));

        return MemberResource::collection($members);
    }

    public function notifications(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->when($request->boolean('unread'), fn ($q) => $q->whereNull('read_at'))
            ->orderBy('created_at', 'desc')
            ->paginate($request->integer('per_page', 20));

        return NotificationResource::collection($notifications);
    }

    public function markNotificationRead(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function auditLogs(Request $request)
    {
        $user = $request->user();

        $query = AuditLog::query()->latest();

        if ($user->organization_id) {
            $query->where('properties->organization_id', $user->organization_id);
        } else {
            $query->where('causer_id', $user->id);
        }

        $logs = $query
            ->with('causer:id,name')
            ->paginate($request->integer('per_page', 20));

        return AuditLogResource::collection($logs);
    }
}
