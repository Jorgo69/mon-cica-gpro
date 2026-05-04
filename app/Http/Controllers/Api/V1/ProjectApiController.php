<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ActivityResource;
use App\Http\Resources\Api\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $projects = Project::query()
            ->visibleTo($user)
            ->withCount('activities')
            ->with(['creator:id,name', 'projectType:id,name'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->orderBy('updated_at', 'desc')
            ->paginate($request->integer('per_page', 15));

        return ProjectResource::collection($projects);
    }

    public function show(Request $request, string $id)
    {
        $project = Project::query()
            ->visibleTo($request->user())
            ->with([
                'creator:id,name',
                'projectType:id,name',
                'budgets',
                'expenses',
                'logicalFramework.specificObjectives.results.activities',
            ])
            ->findOrFail($id);

        return new ProjectResource($project);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'project_type_id' => 'nullable|uuid|exists:project_types,id',
        ]);

        $service = new ProjectService();
        $project = $service->create($data);

        return new ProjectResource($project->load('creator:id,name'));
    }

    public function update(Request $request, string $id)
    {
        $project = Project::query()
            ->visibleTo($request->user())
            ->findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $service = new ProjectService();
        $service->update($project, $data);

        return new ProjectResource($project->fresh());
    }

    public function destroy(Request $request, string $id)
    {
        $project = Project::query()
            ->visibleTo($request->user())
            ->findOrFail($id);

        $project->delete();

        return response()->json(['message' => 'Project deleted.'], 200);
    }

    public function activities(Request $request, string $id)
    {
        $project = Project::query()
            ->visibleTo($request->user())
            ->findOrFail($id);

        $activities = $project->getAllActivities()
            ->load('responsibleUser:id,name,email');

        return ActivityResource::collection($activities);
    }

    public function storeActivity(Request $request, string $id)
    {
        $project = Project::query()
            ->visibleTo($request->user())
            ->with('logicalFramework.specificObjectives.results')
            ->findOrFail($id);

        $data = $request->validate([
            'description' => 'required|string|max:500',
            'result_id' => 'nullable|uuid',
            'responsible_email' => 'nullable|email',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'budget' => 'nullable|numeric|min:0',
        ]);

        // Find result_id — use first result if not specified
        $resultId = $data['result_id'] ?? null;
        if (!$resultId && $project->logicalFramework) {
            $firstSo = $project->logicalFramework->specificObjectives->first();
            $resultId = $firstSo?->results->first()?->id;
        }

        if (!$resultId) {
            return response()->json(['message' => 'No result found to attach the activity to.'], 422);
        }

        // Resolve responsible
        $responsibleId = null;
        if (!empty($data['responsible_email'])) {
            $responsibleId = \App\Models\User::where('email', $data['responsible_email'])
                ->where('organization_id', $project->organization_id)
                ->value('id');
        }

        $activity = \App\Models\Activity::create([
            'description' => $data['description'],
            'result_id' => $resultId,
            'responsible_user_id' => $responsibleId,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'budget' => $data['budget'] ?? 0,
            'status' => \App\Enums\ActivityStatus::PENDING,
            'organization_id' => $project->organization_id,
            'creator_user_id' => $request->user()->id,
        ]);

        return new ActivityResource($activity->load('responsibleUser:id,name,email'));
    }
}
