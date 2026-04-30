<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Types de projet systeme
    |--------------------------------------------------------------------------
    |
    | Ces types sont crees par le seeder et ne peuvent pas etre supprimes.
    | Ils sont visibles par toutes les organisations.
    | Chaque org peut creer ses propres types en plus de ceux-ci.
    |
    */
    'system_project_types' => [
        ['name' => 'Developpement Agricole', 'description' => 'Projets lies a l\'agriculture, l\'elevage et la peche', 'category' => 'Developpement'],
        ['name' => 'Education & Formation', 'description' => 'Projets educatifs, formation professionnelle, alphabetisation', 'category' => 'Social'],
        ['name' => 'Sante & Nutrition', 'description' => 'Projets de sante publique, nutrition, eau et assainissement', 'category' => 'Social'],
        ['name' => 'Infrastructure', 'description' => 'Projets de construction, routes, batiments, equipements', 'category' => 'Infrastructure'],
        ['name' => 'Environnement & Climat', 'description' => 'Projets environnementaux, changement climatique, biodiversite', 'category' => 'Environnement'],
        ['name' => 'Gouvernance & Droits', 'description' => 'Projets de gouvernance, droits humains, democratie', 'category' => 'Gouvernance'],
        ['name' => 'Urgence & Humanitaire', 'description' => 'Reponse aux crises, aide humanitaire, refugies', 'category' => 'Humanitaire'],
        ['name' => 'Developpement Economique', 'description' => 'Micro-finance, entrepreneuriat, emploi des jeunes', 'category' => 'Economie'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Formats de cadre logique disponibles
    |--------------------------------------------------------------------------
    */
    'logframe_formats' => ['matrix', 'tree', 'cards'],

    /*
    |--------------------------------------------------------------------------
    | Limites par defaut (Open Source / Self-hosted)
    |--------------------------------------------------------------------------
    |
    | Ces limites s'appliquent en mode Open Source.
    | En mode SaaS, elles sont gerees par les plans (Phase 8).
    |
    */
    'limits' => [
        'max_organizations' => null, // null = illimite
        'max_users_per_org' => null,
        'max_projects_per_org' => null,
        'max_file_upload_mb' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | PDF Export
    |--------------------------------------------------------------------------
    |
    | Driver : 'dompdf' (defaut, gratuit, zero dependance)
    |          'browsershot' (premium, necessite Chromium + Puppeteer)
    |
    */
    'pdf' => [
        'driver' => env('PDF_DRIVER', 'dompdf'),
        'templates' => [
            'classic' => [
                'name' => 'Classique',
                'description' => 'Mise en page sobre et professionnelle',
                'view' => [
                    'dompdf' => 'pdf.templates.classic-dompdf.index',
                    'browsershot' => 'pdf.templates.classic.index',
                ],
                'premium' => false,
            ],
            'modern' => [
                'name' => 'Moderne',
                'description' => 'Design contemporain avec couleurs et graphiques',
                'view' => [
                    'dompdf' => 'pdf.templates.classic-dompdf.index',
                    'browsershot' => 'pdf.templates.modern.index',
                ],
                'premium' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preferences par defaut
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'locale' => 'fr',
        'theme' => 'light',
        'density' => 'comfortable', // comfortable, compact
        'timezone' => 'Africa/Porto-Novo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'reminder_days' => [7, 3, 1], // J-7, J-3, J-1 avant deadline
        'escalation_after_days' => 7, // Escalade si en retard > 7 jours
        'digest_day' => 'monday', // Jour du resume hebdomadaire
    ],

    /*
    |--------------------------------------------------------------------------
    | Fuseaux horaires disponibles
    |--------------------------------------------------------------------------
    */
    'timezones' => [
        'Africa/Porto-Novo' => 'Benin (GMT+1)',
        'Africa/Abidjan' => 'Cote d\'Ivoire (GMT)',
        'Africa/Dakar' => 'Senegal (GMT)',
        'Africa/Douala' => 'Cameroun (GMT+1)',
        'Africa/Kinshasa' => 'RDC - Kinshasa (GMT+1)',
        'Africa/Lubumbashi' => 'RDC - Lubumbashi (GMT+2)',
        'Africa/Lagos' => 'Nigeria (GMT+1)',
        'Africa/Bamako' => 'Mali (GMT)',
        'Africa/Ouagadougou' => 'Burkina Faso (GMT)',
        'Africa/Niamey' => 'Niger (GMT+1)',
        'Africa/Lome' => 'Togo (GMT)',
        'Africa/Conakry' => 'Guinee (GMT)',
        'Africa/Libreville' => 'Gabon (GMT+1)',
        'Africa/Brazzaville' => 'Congo (GMT+1)',
        'Africa/Bangui' => 'Centrafrique (GMT+1)',
        'Africa/Ndjamena' => 'Tchad (GMT+1)',
        'Europe/Paris' => 'France (GMT+1/+2)',
        'Europe/Brussels' => 'Belgique (GMT+1/+2)',
        'America/New_York' => 'USA Est (GMT-5/-4)',
        'America/Montreal' => 'Canada Est (GMT-5/-4)',
        'UTC' => 'UTC',
    ],
];
