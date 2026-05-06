<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mode de fonctionnement
    |--------------------------------------------------------------------------
    |
    | 'saas'       → ROOT existe, plans actifs, limites respectees, pricing visible
    | 'selfhosted' → pas de ROOT, tout illimite, premier inscrit = ORG_ADMIN
    |
    */
    'mode' => env('GPRO_MODE', 'saas'),
    'version' => env('GPRO_VERSION', '2.1.0'),

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

    /*
    |--------------------------------------------------------------------------
    | Plans et limites
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | Plugins / Marketplace
    |--------------------------------------------------------------------------
    */
    'plugins' => [
        'enabled' => env('GPRO_PLUGINS_ENABLED', true),
        'path' => env('GPRO_PLUGINS_PATH', 'plugins'),
        'catalog_url' => env('GPRO_PLUGIN_CATALOG_URL', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact & liens publics
    |--------------------------------------------------------------------------
    */
    'logo' => env('GPRO_LOGO', null), // path relatif depuis public/ (ex: images/logo.png)
    'logo_dark' => env('GPRO_LOGO_DARK', null), // variante pour dark mode (optionnel)

    'contact' => [
        'whatsapp' => env('GPRO_CONTACT_WHATSAPP', '+22997000000'),
        'phone' => env('GPRO_CONTACT_PHONE', null),
        'email' => env('GPRO_CONTACT_EMAIL', 'contact@cica-gpro.com'),
        'site_url' => env('GPRO_SITE_URL', 'https://cica-gpro.com'),
        'github_url' => env('GPRO_GITHUB_URL', 'https://github.com/cave-tech/cica-gpro'),
        'company' => env('GPRO_COMPANY_NAME', 'Cave-Tech'),
        'company_url' => env('GPRO_COMPANY_URL', 'https://cave-tech.com'),
    ],

    // AI (Groq gratuit prioritaire, Gemini en fallback)
    'ai' => [
        'gemini_api_key' => env('GEMINI_API_KEY'),
        'gemini_model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
        'groq_api_key' => env('GROQ_API_KEY'),
        'groq_model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
    ],

    // Payment
    'payment' => [
        'gateway_url' => env('PAYMENT_GATEWAY_URL'),
        'gateway_name' => env('PAYMENT_GATEWAY_NAME'),
        'contact_whatsapp' => env('PAYMENT_CONTACT_WHATSAPP', '+22997000000'),
        'contact_email' => env('PAYMENT_CONTACT_EMAIL', 'contact@cica-gpro.com'),
    ],

    'plans' => [
        'free' => [
            'price' => '0 FCFA',
            'price_period' => 'mois',
            'limits' => [
                'max_projects' => 2,
                'max_members' => 5,
                'features' => ['basic_export', 'logframe'],
            ],
        ],
        'pro' => [
            'price' => '15 000 FCFA',
            'price_period' => 'mois',
            'limits' => [
                'max_projects' => 20,
                'max_members' => 50,
                'features' => ['basic_export', 'logframe', 'pdf_export', 'excel_export', 'share_link', 'templates', 'indicators', 'budget_tracking'],
            ],
        ],
        'enterprise' => [
            'price' => '45 000 FCFA',
            'price_period' => 'mois',
            'limits' => [
                'max_projects' => -1, // illimite
                'max_members' => -1,
                'features' => ['basic_export', 'logframe', 'pdf_export', 'excel_export', 'share_link', 'templates', 'indicators', 'budget_tracking', 'api_access', 'priority_support', 'multi_currency'],
            ],
        ],
    ],
];
