<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Développement Social',
                'description' => 'Projets axés sur l\'amélioration des conditions de vie sociale.',
                'category' => 'Social',
            ],
            [
                'name' => 'Infrastructure & BTP',
                'description' => 'Construction et réhabilitation d\'infrastructures publiques.',
                'category' => 'Infrastructure',
            ],
            [
                'name' => 'Éducation & Formation',
                'description' => 'Appui au système éducatif et formation professionnelle.',
                'category' => 'Éducation',
            ],
            [
                'name' => 'Santé Publique',
                'description' => 'Amélioration de l\'accès aux soins et santé communautaire.',
                'category' => 'Santé',
            ],
            [
                'name' => 'Environnement',
                'description' => 'Protection de l\'environnement et développement durable.',
                'category' => 'Écologie',
            ],
        ];

        foreach ($types as $type) {
            ProjectType::firstOrCreate(
                ['name' => $type['name']],
                [
                    'id' => (string) Str::uuid(),
                    'description' => $type['description'],
                    'category' => $type['category'],
                ]
            );
        }
    }
}
