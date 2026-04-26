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
                'name' => 'Développement Agricole',
                'description' => 'Soutien aux petits producteurs et mécanisation des cultures.',
                'category' => 'Agriculture',
                'fields' => [
                    ['name' => 'surface_cultivee', 'label' => 'Surface totale cultivée (Ha)', 'type' => \App\Enums\FieldType::NUMBER],
                    ['name' => 'type_culture', 'label' => 'Type de cultures principales', 'type' => \App\Enums\FieldType::TEXT],
                    ['name' => 'zone_intervention', 'label' => 'Zone d\'intervention', 'type' => \App\Enums\FieldType::SELECT, 'options' => [['label' => 'Sud', 'value' => 'sud'], ['label' => 'Centre', 'value' => 'centre'], ['label' => 'Nord', 'value' => 'nord']]],
                ]
            ],
            [
                'name' => 'Eau & Assainissement (WASH)',
                'description' => 'Construction de forages et sensibilisation à l\'hygiène.',
                'category' => 'Infrastructure',
                'fields' => [
                    ['name' => 'nombre_forages', 'label' => 'Nombre de forages prévus', 'type' => \App\Enums\FieldType::NUMBER],
                    ['name' => 'population_cible', 'label' => 'Nombre de bénéficiaires estimés', 'type' => \App\Enums\FieldType::NUMBER],
                    ['name' => 'besoin_latrines', 'label' => 'Construction de latrines requise ?', 'type' => \App\Enums\FieldType::SELECT, 'options' => [['label' => 'Oui', 'value' => 'oui'], ['label' => 'Non', 'value' => 'non']]],
                ]
            ],
            [
                'name' => 'Santé Maternelle',
                'description' => 'Amélioration du suivi prénatal et des accouchements assistés.',
                'category' => 'Santé',
                'fields' => [
                    ['name' => 'centre_sante_cible', 'label' => 'Centres de santé partenaires', 'type' => \App\Enums\FieldType::TEXTAREA],
                    ['name' => 'taux_mortalite_initial', 'label' => 'Taux de mortalité de référence (%)', 'type' => \App\Enums\FieldType::NUMBER],
                ]
            ],
            [
                'name' => 'Éducation Numérique',
                'description' => 'Équipement des écoles en tablettes et accès internet.',
                'category' => 'Éducation',
                'fields' => [
                    ['name' => 'nombre_ecoles', 'label' => 'Nombre d\'établissements', 'type' => \App\Enums\FieldType::NUMBER],
                    ['name' => 'type_materiel', 'label' => 'Type de matériel fourni', 'type' => \App\Enums\FieldType::TEXT],
                ]
            ],
        ];

        foreach ($types as $typeData) {
            $projectType = ProjectType::firstOrCreate(
                ['name' => $typeData['name']],
                [
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'description' => $typeData['description'],
                    'category' => $typeData['category'],
                    'is_system' => true,
                    'is_active' => true,
                ]
            );

            foreach ($typeData['fields'] as $index => $field) {
                \App\Models\DynamicProjectField::firstOrCreate(
                    [
                        'project_type_id' => $projectType->id,
                        'field_name' => $field['name']
                    ],
                    [
                        'question_text' => $field['label'],
                        'input_type' => $field['type'],
                        'options' => $field['options'] ?? null,
                        'order' => $index + 1,
                        'section' => 'Informations Spécifiques',
                        'is_required' => true,
                        'target_project_field' => 'custom_' . $field['name'],
                        'delimiter_start' => '[[' . $field['name'] . ']]',
                        'delimiter_end' => '[[/' . $field['name'] . ']]',
                        'render_as' => 'text',
                    ]
                );
            }
        }
    }
}
