<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Activity::query()
            ->with(['responsibleUser:id,name,email', 'result.specificObjective.logicalFramework.project:id,title']);

        if ($user->organization_id) {
            $query->where('organization_id', $user->organization_id);
        } else {
            $query->where('creator_user_id', $user->id);
        }

        $activities = $query
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->orderBy('updated_at', 'desc')
            ->paginate($request->integer('per_page', 20));

        return ActivityResource::collection($activities);
    }

    public function show(Request $request, string $id)
    {
        $activity = $this->findActivity($request, $id);
        return new ActivityResource($activity->load('responsibleUser:id,name,email'));
    }

    public function update(Request $request, string $id)
    {
        $activity = $this->findActivity($request, $id);

        $data = $request->validate([
            'description' => 'sometimes|string|max:500',
            'status' => 'sometimes|string',
            'progress_percentage' => 'sometimes|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget' => 'nullable|numeric|min:0',
        ]);

        if (isset($data['status'])) {
            $status = \App\Enums\ActivityStatus::tryFrom($data['status']);
            if ($status) $data['status'] = $status;
        }

        $activity->update($data);

        return new ActivityResource($activity->fresh()->load('responsibleUser:id,name,email'));
    }

    protected function findActivity(Request $request, string $id): Activity
    {
        $user = $request->user();

        $query = Activity::query();
        if ($user->organization_id) {
            $query->where('organization_id', $user->organization_id);
        } else {
            $query->where('creator_user_id', $user->id);
        }

        return $query->findOrFail($id);
    }
}
