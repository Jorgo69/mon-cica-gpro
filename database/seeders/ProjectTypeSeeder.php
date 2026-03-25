<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProjectType;
use App\Models\DynamicProjectField;
use App\Enums\FieldType;
use Illuminate\Database\Seeder;

class ProjectTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name'        => 'Développement Agricole',
                'description' => 'Soutien aux petits producteurs et mécanisation des cultures.',
                'category'    => 'Agriculture',
                'fields'      => [
                    ['name' => 'surface_cultivee',   'label' => 'Surface totale cultivée (Ha)',        'type' => FieldType::NUMBER],
                    ['name' => 'type_culture',        'label' => 'Type de cultures principales',       'type' => FieldType::TEXT],
                    ['name' => 'zone_intervention',   'label' => 'Zone d\'intervention',               'type' => FieldType::SELECT,
                        'options' => [['label' => 'Sud', 'value' => 'sud'], ['label' => 'Centre', 'value' => 'centre'], ['label' => 'Nord', 'value' => 'nord']]],
                ],
            ],
            [
                'name'        => 'Eau & Assainissement (WASH)',
                'description' => 'Construction de forages et sensibilisation à l\'hygiène.',
                'category'    => 'Eau & Hygiène',
                'fields'      => [
                    ['name' => 'nombre_forages',    'label' => 'Nombre de forages prévus',          'type' => FieldType::NUMBER],
                    ['name' => 'population_cible',  'label' => 'Nombre de bénéficiaires estimés',   'type' => FieldType::NUMBER],
                    ['name' => 'besoin_latrines',   'label' => 'Construction de latrines requise ?', 'type' => FieldType::SELECT,
                        'options' => [['label' => 'Oui', 'value' => 'oui'], ['label' => 'Non', 'value' => 'non']]],
                ],
            ],
            [
                'name'        => 'Santé Maternelle',
                'description' => 'Amélioration du suivi prénatal et des accouchements assistés.',
                'category'    => 'Santé',
                'fields'      => [
                    ['name' => 'centre_sante_cible',      'label' => 'Centres de santé partenaires',         'type' => FieldType::TEXTAREA],
                    ['name' => 'taux_mortalite_initial',  'label' => 'Taux de mortalité de référence (%)',   'type' => FieldType::NUMBER],
                ],
            ],
            [
                'name'        => 'Éducation Numérique',
                'description' => 'Équipement des écoles en tablettes et accès internet.',
                'category'    => 'Éducation',
                'fields'      => [
                    ['name' => 'nombre_ecoles',  'label' => 'Nombre d\'établissements',  'type' => FieldType::NUMBER],
                    ['name' => 'type_materiel',  'label' => 'Type de matériel fourni',   'type' => FieldType::TEXT],
                ],
            ],
        ];

        foreach ($types as $typeData) {
            $category = Category::where('name', $typeData['category'])
                ->where('type', 'project_type_category')
                ->whereNull('organization_id')
                ->first();

            $projectType = ProjectType::firstOrCreate(
                ['name' => $typeData['name'], 'organization_id' => null],
                [
                    'description' => $typeData['description'],
                    'category_id' => $category?->id,
                ]
            );

            foreach ($typeData['fields'] as $index => $field) {
                DynamicProjectField::firstOrCreate(
                    ['project_type_id' => $projectType->id, 'field_name' => $field['name']],
                    [
                        'question_text'       => $field['label'],
                        'input_type'          => $field['type'],
                        'options'             => $field['options'] ?? null,
                        'order'               => $index + 1,
                        'section'             => 'Informations Spécifiques',
                        'is_required'         => true,
                        'target_project_field' => 'custom_' . $field['name'],
                        'delimiter_start'     => '[[' . $field['name'] . ']]',
                        'delimiter_end'       => '[[/' . $field['name'] . ']]',
                        'render_as'           => 'text',
                    ]
                );
            }
        }
    }
}
