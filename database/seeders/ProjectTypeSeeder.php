<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use App\Models\DynamicProjectField;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProjectTypeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Types systeme depuis config/gpro.php (base, sans champs dynamiques)
        foreach (config('gpro.system_project_types', []) as $typeData) {
            ProjectType::firstOrCreate(
                ['name' => $typeData['name']],
                [
                    'id' => (string) Str::orderedUuid(),
                    'description' => $typeData['description'],
                    'category' => $typeData['category'],
                    'is_system' => true,
                    'is_active' => true,
                ]
            );
        }

        // 2. Types enrichis avec champs dynamiques (demo ONG)
        $enrichedTypes = [
            [
                'name' => 'Developpement Agricole',
                'fields' => [
                    ['name' => 'surface_cultivee', 'label' => 'Surface totale cultivee (Ha)', 'type' => \App\Enums\FieldType::NUMBER],
                    ['name' => 'type_culture', 'label' => 'Type de cultures principales', 'type' => \App\Enums\FieldType::TEXT],
                    ['name' => 'zone_intervention', 'label' => 'Zone d\'intervention', 'type' => \App\Enums\FieldType::SELECT, 'options' => [['label' => 'Sud', 'value' => 'sud'], ['label' => 'Centre', 'value' => 'centre'], ['label' => 'Nord', 'value' => 'nord']]],
                ]
            ],
            [
                'name' => 'Sante & Nutrition',
                'fields' => [
                    ['name' => 'centre_sante_cible', 'label' => 'Centres de sante partenaires', 'type' => \App\Enums\FieldType::TEXTAREA],
                    ['name' => 'taux_mortalite_initial', 'label' => 'Taux de mortalite de reference (%)', 'type' => \App\Enums\FieldType::NUMBER],
                ]
            ],
            [
                'name' => 'Education & Formation',
                'fields' => [
                    ['name' => 'nombre_ecoles', 'label' => 'Nombre d\'etablissements', 'type' => \App\Enums\FieldType::NUMBER],
                    ['name' => 'type_materiel', 'label' => 'Type de materiel fourni', 'type' => \App\Enums\FieldType::TEXT],
                ]
            ],
            [
                'name' => 'Infrastructure',
                'fields' => [
                    ['name' => 'nombre_forages', 'label' => 'Nombre de forages prevus', 'type' => \App\Enums\FieldType::NUMBER],
                    ['name' => 'population_cible', 'label' => 'Nombre de beneficiaires estimes', 'type' => \App\Enums\FieldType::NUMBER],
                ]
            ],
        ];

        foreach ($enrichedTypes as $typeData) {
            $projectType = ProjectType::where('name', $typeData['name'])->first();

            if ($projectType) {
                foreach ($typeData['fields'] as $index => $field) {
                    DynamicProjectField::firstOrCreate(
                        ['project_type_id' => $projectType->id, 'field_name' => $field['name']],
                        [
                            'question_text' => $field['label'],
                            'input_type' => $field['type'],
                            'options' => $field['options'] ?? null,
                            'order' => $index + 1,
                            'section' => 'Informations Specifiques',
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
}
