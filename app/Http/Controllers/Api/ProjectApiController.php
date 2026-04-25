<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="Liste tous les projets de l'organisation",
     *     tags={"Projects"},
     *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des projets paginée"
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     summary="Récupérer les détails d'un projet spécifique",
     *     tags={"Projects"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du projet",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(response=200, description="Détails du projet"),
     *     @OA\Response(response=404, description="Projet non trouvé")
     * )
     */
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
