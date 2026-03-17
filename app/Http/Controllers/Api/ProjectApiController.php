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
     *     @OA\Response(
     *         response=200,
     *         description="Liste des projets récupérée avec succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Project")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié"
     *     )
     * )
     */
    public function index()
    {
        $projects = Project::all();
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
     *     @OA\Response(
     *         response=200,
     *         description="Détails du projet",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Projet non trouvé"
     *     )
     * )
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }
}
