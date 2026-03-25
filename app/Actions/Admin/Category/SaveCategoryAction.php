<?php

namespace App\Actions\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class SaveCategoryAction
{
    /**
     * Crée ou met à jour une catégorie de type projet.
     *
     * @param array  $data            Les données validées (name, description).
     * @param string|null $categoryId L'ID de la catégorie à mettre à jour, ou null pour créer.
     * @return GeneralAdministration
     */
    public function execute(array $data, ?string $categoryId = null): GeneralAdministration
    {
        return DB::transaction(function () use ($data, $categoryId) {
            return Category::updateOrCreate(
                ['id' => $categoryId],
                [
                    'name'        => $data['name'],
                    'description' => $data['description'] ?? null,
                    'type'        => 'project_type_category',
                ]
            );
        });
    }
}
