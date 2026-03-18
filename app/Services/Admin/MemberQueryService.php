<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class MemberQueryService
{
    /**
     * Récupère la liste des membres filtrée par organisation, recherche et tri.
     *
     * @param string $search
     * @param string $sortField
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function list(
        string $search = '',
        string $sortField = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $user = auth()->user();
        
        $query = User::query();

        // Scoping par organisation :
        // Si l'utilisateur n'est PAS IT_ADMIN (Super Admin) et qu'il a une organisation, on filtre.
        if ($user && !$user->hasRole('IT_ADMIN') && $user->organization_id) {
            $query->where('organization_id', $user->organization_id);
        }

        // Recherche
        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // Champs de tri autorisés
        $allowedSorts = ['name', 'email', 'created_at', 'department', 'role'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }

        return $query
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }

    /**
     * Récupère un membre spécifique par son ID, avec respect du scoping.
     */
    public function findById(string $id): ?User
    {
        $user = auth()->user();
        $query = User::query();

        if ($user && !$user->hasRole('IT_ADMIN') && $user->organization_id) {
            $query->where('organization_id', $user->organization_id);
        }
        
        return $query->find($id);
    }
}
