<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class MemberQueryService
{
    /**
     * Recupere la liste des membres filtree par organisation (via Global Scope), recherche et tri.
     */
    public function list(
        string $search = '',
        string $sortField = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        // Le Global Scope Multitenantable sur User filtre automatiquement par org
        $query = User::query();

        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $allowedSorts = ['name', 'email', 'created_at', 'department', 'role'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }

        return $query
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }

    /**
     * Recupere un membre specifique par son ID (scope automatique via Multitenantable).
     */
    public function findById(string $id): ?User
    {
        return User::find($id);
    }
}
