<?php

namespace Database\Seeders;

use App\Enums\AdminCategoryType;
use App\Models\GeneralAdministration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GeneralAdministrationSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            AdminCategoryType::PROJECT_CATEGORY->value => [
                'Agriculture & Elevage' => 'Projets lies au secteur agricole et pastoral',
                'Education' => 'Projets educatifs et de formation',
                'Sante' => 'Projets de sante publique et nutrition',
                'Infrastructure' => 'Routes, batiments, equipements',
                'Environnement' => 'Projets environnementaux et climatiques',
                'Gouvernance' => 'Droits humains, democratie, institutions',
                'Humanitaire' => 'Urgences, refugies, aide alimentaire',
                'Economie' => 'Micro-finance, emploi, entrepreneuriat',
            ],
            AdminCategoryType::BUDGET_CATEGORY->value => [
                'Personnel' => 'Salaires, indemnites, consultants',
                'Equipement' => 'Materiel, vehicules, informatique',
                'Fonctionnement' => 'Loyer, fournitures, communication',
                'Deplacement' => 'Transport, missions, per diem',
                'Formation' => 'Ateliers, seminaires, supports',
                'Sous-traitance' => 'Prestataires externes, etudes',
            ],
            AdminCategoryType::RESOURCE_TYPE->value => [
                'Humaine' => 'Personnel, consultants, benevoles',
                'Materielle' => 'Equipements, vehicules, mobilier',
                'Financiere' => 'Fonds, subventions, prets',
                'Technique' => 'Logiciels, outils, methodologies',
            ],
            AdminCategoryType::DOCUMENT_TYPE->value => [
                'Rapport' => 'Rapports narratifs et financiers',
                'Contrat' => 'Conventions, accords, MoU',
                'Etude' => 'Etudes de faisabilite, evaluations',
                'Communication' => 'Brochures, presentations, photos',
                'Administratif' => 'PV, courriers, attestations',
            ],
        ];

        foreach ($categories as $type => $items) {
            foreach ($items as $name => $description) {
                GeneralAdministration::firstOrCreate(
                    ['name' => $name, 'type' => $type],
                    [
                        'id' => (string) Str::orderedUuid(),
                        'description' => $description,
                        'is_system' => true,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
