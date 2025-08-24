<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\GeneralAdministration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GeneralAdministrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectTypes = [
            'Brouillon'    => 'Projet en phase de conception',
            'En Attente'   => 'Projet en attente de validation ou de démarrage',
            'En Cours'     => 'Projet actuellement en exécution',
            'Suspendu'     => 'Projet temporairement suspendu',
            'Accepté'      => 'Projet accepté et validé',
            'Rejeté'       => 'Projet refusé après évaluation',
            'En Arrêté'    => 'Projet arrêté avant sa fin prévue',
        ];

        foreach ($projectTypes as $name => $description) {
            GeneralAdministration::firstOrCreate(
                [
                    'name' => $name,
                    'type' => 'project_type',
                ],
                [
                    'id'          => (string) Str::uuid(),
                    'description' => $description,
                ]
            );
        }
    }
}
