<?php

return [

    // Navbar
    'nav' => [
        'solutions' => 'Solutions',
        'expertise' => 'Expertise',
        'process' => 'Process',
        'faq' => 'FAQ',
        'contact' => 'Contact',
        'pricing' => 'Pricing',
        'login' => 'Login',
        'start' => 'Get Started',
        'start_mobile' => 'Start the experience',
        'dashboard' => 'Dashboard',
        'portal' => 'Client Portal',
        'lang_switch' => 'FR',
    ],

    // Hero
    'hero' => [
        'badge' => 'Logical Framework Platform',
        'title_line1' => 'Structure. Manage.',
        'title_line2' => 'Measure the',
        'title_highlight' => 'Impact',
        'subtitle' => 'The platform that turns your logical frameworks into living projects. Budgets, indicators, reports — from design to final review.',
        'cta_start' => 'Create a project',
        'cta_discover' => 'Explore solutions',
    ],

    // Stats
    'stats' => [
        ['462+', 'Automated tests'],
        ['9', 'AI Providers'],
        ['15', 'API Endpoints'],
        ['8', 'Currencies supported'],
    ],

    // Solutions
    'solutions' => [
        'tag' => 'Solutions',
        'title' => 'An ecosystem built for high performance.',
        'subtitle' => 'Every module is calibrated to meet the standards of the Logical Framework Approach (LFA) and Results-Based Management (RBM).',
        'items' => [
            ['target', 'Complete Logical Framework', 'General objective, specific objectives, results, activities and sub-activities. Three display formats.'],
            ['bar-chart-3', 'Budget Management', 'Planned budget vs actual expenses, multi-currency, burn rate, automatic overspending alerts.'],
            ['brain', 'Configurable AI', '9 providers (Groq, OpenAI, Anthropic, Gemini...). Per-field assistance, executive summary, dashboard analysis.'],
        ],
        'cards' => [
            'kpi' => 'KPI Tracking',
            'kpi_desc' => 'Indicators, measurements, trends and automatic alerts.',
            'compliance' => '100%',
            'compliance_label' => 'Standards Compliance',
            'expertise' => 'Integrated Expertise',
            'security' => 'Security & GDPR',
            'security_desc' => 'Strict multi-tenant, full audit, export and anonymization.',
        ],
    ],

    // Features (full grid)
    'features' => [
        'tag' => 'Features',
        'title' => 'Everything you need',
        'items' => [
            ['target', 'Logical Framework', 'Complete hierarchical structure: general objective, specific objectives, results, activities, sub-activities. 3 display formats.'],
            ['bar-chart-3', 'Budgets & Finance', 'Planned budget vs actual expenses, multi-currency (8 currencies), burn rate, overspending alerts.'],
            ['users', 'Multi-tenant', 'Each organization has its own isolated space. 4-level RBAC with granular permissions.'],
            ['brain', 'Integrated AI', '9 providers (Groq, OpenAI, Anthropic, Gemini...). Per-field assistance, executive summary, dashboard analysis.'],
            ['file-text', 'Exports', 'PDF, Word, multi-sheet Excel. Automatic quarterly reports. Public donor dashboard.'],
            ['calendar', 'Calendar', '6 views (year, semester, quarter, month, week, day). iCal export, Google Calendar / Outlook sync.'],
            ['activity', 'Indicators', 'Progress tracking with measurements, trends, automatic alerts (stagnation, regression).'],
            ['globe', 'REST API v1', '15 endpoints, Sanctum Bearer token auth, rate limiting. Full documentation.'],
            ['webhook', 'Webhooks', '10 events, HMAC-SHA256 signature, auto-disable after 10 failures.'],
            ['map-pin', 'Project Map', 'Geographic visualization (Leaflet), 40 geocoded countries, status-colored markers.'],
            ['puzzle', 'Marketplace', 'Extensible plugin system. 8 hooks available. Create your own extensions.'],
            ['shield-check', 'GDPR', 'Data export, anonymization, scheduled deletion. Full compliance.'],
        ],
    ],

    // Open Source
    'opensource' => [
        'tag' => 'Open Source',
        'title' => 'Two modes, one tool.',
        'subtitle' => 'CICA-GPRO is available as open source (MIT). Install on your server or use the hosted SaaS version.',
        'selfhosted' => 'Self-hosted',
        'selfhosted_desc' => 'Install on your server. Everything unlimited. Docker included.',
        'saas' => 'SaaS',
        'saas_desc' => 'Hosted version with plans. Zero maintenance.',
        'terminal_comment1' => '# App running at http://localhost:8080',
        'terminal_comment2' => '# phpMyAdmin at http://localhost:8081',
    ],

    // Process
    'process' => [
        'tag' => 'The Process',
        'title_start' => 'From logical framework to final report in',
        'title_italic' => '4 steps',
        'steps' => [
            ['01', 'Analysis', 'Identification of problems and strategic objectives.', 'search'],
            ['02', 'Planning', 'Development of the matrix and action plan.', 'list-todo'],
            ['03', 'Execution', 'Activities, resources and daily monitoring.', 'play'],
            ['04', 'Reporting', 'Export PDF, Word, Excel. Donor sharing.', 'file-pie-chart'],
        ],
    ],

    // FAQ
    'faq' => [
        'tag' => 'FAQ',
        'title' => 'Frequently Asked Questions',
        'items' => [
            ['What is the Logical Framework Approach (LFA)?', 'The LFA is a participatory methodology used to design, implement and evaluate projects. It structures objectives and activities in a coherent manner.'],
            ['Is it suitable for small organizations?', 'Absolutely. In self-hosted mode, everything is free and unlimited. The interface guides users step by step with the onboarding system and built-in FAQ.'],
            ['What export formats are supported?', 'PDF (DomPDF or Chromium), Word (PHPWord), multi-sheet Excel. Reports can be automatically generated every quarter.'],
            ['Is my data secure?', 'Strict multi-tenant architecture, encrypted API keys, complete audit trail, GDPR compliance. In self-hosted mode, your data stays on your server.'],
            ['Is AI mandatory?', 'No. AI is optional and disabled by default. If configured (9 providers supported), it assists writing, generates summaries and analyzes your dashboards.'],
            ['Can I integrate GPRO with other tools?', 'Yes. REST API v1 (15 endpoints), HMAC-SHA256 webhooks, iCal export, extensible plugin system.'],
        ],
    ],

    // Trust
    'trust' => [
        'tag' => 'They trust us',
        'placeholder' => 'Your logo here',
    ],

    // CTA
    'cta' => [
        'title' => 'Ready to structure your projects?',
        'subtitle' => 'Join the organizations using :app to maximize the impact of their development projects.',
        'button' => 'Create my account',
        'contact' => 'Contact us',
    ],

    // Footer
    'footer' => [
        'description' => 'Open source project management platform based on the Logical Framework. Designed for NGOs and development organizations.',
        'col_platform' => 'Platform',
        'col_resources' => 'Resources',
        'col_contact' => 'Contact',
        'solutions' => 'Solutions',
        'expertise' => 'Expertise',
        'pricing' => 'Pricing',
        'portal' => 'Client Portal',
        'register' => 'Sign up',
        'faq' => 'Help & FAQ',
        'privacy' => 'Privacy',
        'terms' => 'Terms',
        'github' => 'GitHub',
        'copyright' => 'Open source under MIT license.',
        'built_by' => 'Built by',
        'back_to_top' => 'Back to top',
    ],
];
