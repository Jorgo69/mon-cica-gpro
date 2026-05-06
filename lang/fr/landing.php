<?php

return [

    // Navbar
    'nav' => [
        'solutions' => 'Solutions',
        'expertise' => 'Expertise',
        'process' => 'Processus',
        'faq' => 'FAQ',
        'contact' => 'Contact',
        'pricing' => 'Tarifs',
        'login' => 'Connexion',
        'start' => 'Debuter',
        'start_mobile' => 'Debuter l\'experience',
        'dashboard' => 'Mon Espace',
        'portal' => 'Portail Client',
        'lang_switch' => 'EN',
    ],

    // Hero
    'hero' => [
        'badge' => 'Plateforme de Cadre Logique',
        'title_line1' => 'Structurez. Pilotez.',
        'title_line2' => 'Mesurez l\'',
        'title_highlight' => 'Impact',
        'subtitle' => 'La plateforme qui transforme vos cadres logiques en projets vivants. Budgets, indicateurs, rapports — de la conception au bilan final.',
        'cta_start' => 'Creer un projet',
        'cta_discover' => 'Explorer les solutions',
    ],

    // Stats
    'stats' => [
        ['462+', 'Tests automatises'],
        ['9', 'Providers IA'],
        ['15', 'Endpoints API'],
        ['8', 'Devises supportees'],
    ],

    // Solutions
    'solutions' => [
        'tag' => 'Solutions',
        'title' => 'Un ecosysteme concu pour la haute performance.',
        'subtitle' => 'Chaque module est calibre pour repondre aux standards de l\'Approche du Cadre Logique (ACL) et de la Gestion Axee sur les Resultats (GAR).',
        'items' => [
            ['target', 'Cadre Logique Complet', 'Objectif general, objectifs specifiques, resultats, activites et sous-activites. Trois formats de visualisation.'],
            ['bar-chart-3', 'Pilotage Budgetaire', 'Budget planifie vs depenses reelles, multi-devise, burn rate, alertes de depassement automatiques.'],
            ['brain', 'IA Configurable', '9 providers (Groq, OpenAI, Anthropic, Gemini...). Assistance par champ, resume executif, analyse dashboard.'],
        ],
        'cards' => [
            'kpi' => 'Suivi KPI',
            'kpi_desc' => 'Indicateurs, mesures, tendances et alertes automatiques.',
            'compliance' => '100%',
            'compliance_label' => 'Conformite Standards',
            'expertise' => 'Expertise Integree',
            'security' => 'Securite & RGPD',
            'security_desc' => 'Multi-tenant strict, audit complet, export et anonymisation.',
        ],
    ],

    // Open Source
    'opensource' => [
        'tag' => 'Open Source',
        'title' => 'Deux modes, un seul outil.',
        'subtitle' => 'CICA-GPRO est disponible en open source (MIT). Installez-le sur votre serveur ou utilisez la version SaaS hebergee.',
        'selfhosted' => 'Self-hosted',
        'selfhosted_desc' => 'Installez sur votre serveur. Tout illimite. Docker inclus.',
        'saas' => 'SaaS',
        'saas_desc' => 'Version hebergee avec plans. Zero maintenance.',
        'terminal_comment1' => '# Application sur http://localhost:8080',
        'terminal_comment2' => '# phpMyAdmin sur http://localhost:8081',
    ],

    // Process
    'process' => [
        'tag' => 'Le Processus',
        'title_start' => 'Du cadre logique au rapport final en',
        'title_italic' => '4 etapes',
        'steps' => [
            ['01', 'Analyse', 'Identification des problemes et objectifs strategiques.', 'search'],
            ['02', 'Planification', 'Elaboration de la matrice et du plan d\'action.', 'list-todo'],
            ['03', 'Execution', 'Activites, ressources et suivi au quotidien.', 'play'],
            ['04', 'Reporting', 'Export PDF, Word, Excel. Partage bailleur.', 'file-pie-chart'],
        ],
    ],

    // FAQ
    'faq' => [
        'tag' => 'FAQ',
        'title' => 'Questions Frequentes',
        'items' => [
            ['Qu\'est-ce que l\'Approche du Cadre Logique (ACL) ?', 'L\'ACL est une methodologie participative utilisee pour concevoir, executer et evaluer des projets. Elle permet de structurer les objectifs et les activites de maniere coherente.'],
            ['Est-ce adapte aux petites structures ?', 'Absolument. En mode selfhosted, tout est gratuit et illimite. L\'interface guide les utilisateurs pas a pas grace au systeme d\'onboarding et a la FAQ integree.'],
            ['Quels formats d\'export sont supportes ?', 'PDF (DomPDF ou Chromium), Word (PHPWord), Excel multi-feuilles. Les rapports peuvent etre generes automatiquement chaque trimestre.'],
            ['Mes donnees sont-elles securisees ?', 'Architecture multi-tenant stricte, chiffrement des cles API, audit complet, conformite RGPD. En selfhosted, vos donnees restent sur votre serveur.'],
            ['L\'IA est-elle obligatoire ?', 'Non. L\'IA est optionnelle et desactivee par defaut. Si configuree (9 providers supportes), elle assiste la redaction, genere des resumes et analyse vos tableaux de bord.'],
            ['Puis-je integrer GPRO avec d\'autres outils ?', 'Oui. API REST v1 (15 endpoints), webhooks HMAC-SHA256, export iCal, systeme de plugins extensible.'],
        ],
    ],

    // Trust
    'trust' => [
        'tag' => 'Ils nous font confiance',
        'placeholder' => 'Votre logo ici',
    ],

    // CTA
    'cta' => [
        'title' => 'Pret a structurer vos projets ?',
        'subtitle' => 'Rejoignez les organisations qui utilisent :app pour maximiser l\'impact de leurs projets de developpement.',
        'button' => 'Creer mon compte',
        'contact' => 'Nous contacter',
    ],

    // Footer
    'footer' => [
        'description' => 'Plateforme open source de gestion de projets basee sur le Cadre Logique. Concue pour les ONG et organisations de developpement.',
        'col_platform' => 'Plateforme',
        'col_resources' => 'Ressources',
        'col_contact' => 'Contact',
        'solutions' => 'Solutions',
        'expertise' => 'Expertise',
        'pricing' => 'Tarifs',
        'portal' => 'Portail Client',
        'register' => 'Inscription',
        'faq' => 'Aide & FAQ',
        'privacy' => 'Confidentialite',
        'terms' => 'Conditions',
        'github' => 'GitHub',
        'copyright' => 'Open source sous licence MIT.',
        'built_by' => 'Developpe par',
        'back_to_top' => 'Retour en haut',
    ],
];
