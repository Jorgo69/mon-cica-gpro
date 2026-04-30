<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 15), 100);

        $projects = Project::query()
            ->when($request->input('search'), fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json($projects);
    }

    public function show($id)
    {
        $project = Project::with([
            'logicalFramework.specificObjectives.results.activities',
            'budgets',
            'creator',
        ])->findOrFail($id);

        return response()->json($project);
    }
}
