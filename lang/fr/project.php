<?php

return [
    'step_1' => [
        'title' => 'informations clés',
        'description' => 'détails de base du projet',
        'form' => [
            'title' => 'informations clés du projet',
            'description' => 'renseignez les détails administratifs de base et choisissez le type de projet',
            'input_1' => 'type de projet',
            'option' => 'sélectionner un type de projet',
            'select' => 'description du type de projet',
            'input_2' => 'titre du projet',
            'input_3' => 'code du projet',
            'input_4' => 'titre abrégé (optionnel)',
            'input_5' => 'date de debut',
            'input_6' => 'date de fin',
        ],
        'type_message' => 'aucun champ dynamique configuré pour ce type de projet'
    ],
    'step_2' => [
        'title' => 'contexte & documents',
        'description' => 'description et fichiers pertinents',
    ],
    'step_3' => [
        'title' => 'cadre logique',
        'description' => 'but et objectifs spécifiques',
    ],
    'step_4' => [
        'title' => 'résultats attendus',
        'description' => 'livrables concrets du projet',
    ],
    'step_5' => [
        'title' => 'contexte & documents',
        'description' => 'description et fichiers pertinents',
    ],
    'step_6' => [
        'title' => 'finalisation',
        'description' => 'vérification et soumission',
    ],
    
];