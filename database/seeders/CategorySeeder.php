<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Catégories de types de projets — configurables par organisation (null = système global)
        $categories = [
            ['name' => 'Santé',           'description' => 'Projets liés à la santé et au bien-être',           'meta' => ['color' => 'rose',    'icon' => 'heart']],
            ['name' => 'Éducation',       'description' => 'Projets liés à l\'éducation et à la formation',     'meta' => ['color' => 'blue',    'icon' => 'book']],
            ['name' => 'Agriculture',     'description' => 'Projets liés à l\'agriculture et à l\'alimentation', 'meta' => ['color' => 'green',   'icon' => 'sprout']],
            ['name' => 'Eau & Hygiène',   'description' => 'Projets WASH : eau, assainissement, hygiène',       'meta' => ['color' => 'cyan',    'icon' => 'droplets']],
            ['name' => 'Infrastructure',  'description' => 'Projets de construction et d\'équipement',          'meta' => ['color' => 'orange',  'icon' => 'building']],
            ['name' => 'Numérique',       'description' => 'Projets liés au numérique et à la technologie',     'meta' => ['color' => 'violet',  'icon' => 'monitor']],
            ['name' => 'Environnement',   'description' => 'Projets liés à l\'environnement et au climat',      'meta' => ['color' => 'emerald', 'icon' => 'leaf']],
            ['name' => 'Social',          'description' => 'Projets d\'inclusion et de cohésion sociale',       'meta' => ['color' => 'amber',   'icon' => 'users']],
        ];

        foreach ($categories as $i => $data) {
            Category::firstOrCreate(
                ['name' => $data['name'], 'organization_id' => null, 'type' => 'project_type_category'],
                array_merge($data, ['type' => 'project_type_category', 'order' => $i])
            );
        }
    }
}
