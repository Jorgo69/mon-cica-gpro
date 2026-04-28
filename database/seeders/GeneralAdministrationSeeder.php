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
        $activityCategory = [
            'Brouillon'  => 'Activite en phase de conception',
            'Abandonné' => 'Activite abandonne',
            'En Arrêté'    => 'Activite arrêté avant sa fin prévue',
            'En Attente' => 'Activite mis en pause',
            'En Cours'     => 'Activite actuellement en exécution',
            'Suspendu'   => 'Activite temporairement suspendu',
            'Terminé'    => 'Activite arrêté avant sa fin prévue',
        ];

        foreach ($projectTypes as $name => $description) {
            GeneralAdministration::firstOrCreate(
                [
                    'name' => $name,
                    'type' => 'project_type',
                ],
                [
                    'id'          => (string) Str::orderedUuid(),
                    'description' => $description,
                    'is_system'   => true,
                    'is_active'   => true,
                ]
            );
        }
        foreach ($activityCategory as $name => $description) {
            GeneralAdministration::firstOrCreate(
                [
                    'name' => $name,
                    'type' => 'activity_status',
                ],
                [
                    'id'          => (string) Str::orderedUuid(),
                    'description' => $description,
                    'is_system'   => true,
                    'is_active'   => true,
                ]
            );
        }
    }
}
