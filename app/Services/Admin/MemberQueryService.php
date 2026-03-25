<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Enums\AccountType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class MemberQueryService
{
    public function list(
        string $search = '',
        string $sortField = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 10
    ): LengthAwarePaginator {
        $user = auth()->user();
        $orgId = session('current_organization_id');

        $query = User::query();

        // Scoping: members de l'org active (sauf system_admin → voit tout)
        if ($user && $user->account_type !== AccountType::SYSTEM_ADMIN && $orgId) {
            $query->whereHas('organizations', fn($q) => $q->where('organizations.id', $orgId));
        }

        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        $allowedSorts = ['name', 'email', 'created_at', 'account_type'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }

        return $query->orderBy($sortField, $sortDirection)->paginate($perPage);
    }

    public function findById(string $id): ?User
    {
        $user = auth()->user();
        $orgId = session('current_organization_id');
        $query = User::query();

        if ($user && $user->account_type !== AccountType::SYSTEM_ADMIN && $orgId) {
            $query->whereHas('organizations', fn($q) => $q->where('organizations.id', $orgId));
        }

        return $query->find($id);
    }
}
