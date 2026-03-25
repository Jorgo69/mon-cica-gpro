<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryQueryService
{
    /**
     * Récupère les catégories filtrées, triées et paginées.
     *
     * @param string $search        Terme de recherche.
     * @param string $sortField     Champ de tri.
     * @param string $sortDirection Direction du tri (asc/desc).
     * @param int    $perPage       Nombre d'éléments par page.
     * @return LengthAwarePaginator
     */
    public function list(
        string $search = '',
        string $sortField = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Category::query()
            ->where('type', 'project_type_category');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Empêcher le tri par ID
        $allowedSorts = ['name', 'description', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }

        return $query
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }
}
