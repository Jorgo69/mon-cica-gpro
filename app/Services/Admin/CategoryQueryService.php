<?php

namespace App\Services\Admin;

use App\Enums\AdminCategoryType;
use App\Models\GeneralAdministration;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryQueryService
{
    public function list(
        ?AdminCategoryType $type = null,
        string $search = '',
        string $sortField = 'name',
        string $sortDirection = 'asc',
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = GeneralAdministration::query()
            ->where('is_active', true);

        if ($type) {
            $query->where('type', $type);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $allowedSorts = ['name', 'type', 'description', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'name';
        }

        return $query->orderBy($sortField, $sortDirection)->paginate($perPage);
    }

    public function byType(AdminCategoryType $type): Collection
    {
        return GeneralAdministration::where('type', $type)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
