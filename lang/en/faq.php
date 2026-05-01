<?php

return [

    'title' => 'Frequently Asked Questions',
    'subtitle' => 'Find answers to the most common questions',
    'search_placeholder' => 'Search a question...',
    'no_results' => 'No results for this search.',

    'categories' => [
        'general' => 'General',
        'projects' => 'Projects',
        'collaboration' => 'Collaboration',
        'budget' => 'Budget & Finance',
        'export' => 'Exports & Reports',
        'account' => 'Account & Security',
    ],

    'items' => [
        [
            'category' => 'general',
            'q' => 'What is CICA-GPRO?',
            'a' => 'CICA-GPRO is an intelligent project management system designed for NGOs and international organizations. It manages the complete project cycle: logical framework, activities, budgets, indicators, reports.',
        ],
        [
            'category' => 'general',
            'q' => 'What are the different user roles?',
            'a' => '<strong>ORG_ADMIN</strong>: Organization administrator (full management). <strong>MANAGER</strong>: Project manager (creation, tracking). <strong>MEMBER</strong>: Team member (create projects, track activities). <strong>INDEPENDENT</strong>: Solo user without organization.',
        ],
        [
            'category' => 'general',
            'q' => 'How to change the language?',
            'a' => 'Go to Settings > Language. You can choose between French and English. The language applies immediately.',
        ],
        [
            'category' => 'projects',
            'q' => 'How to create a project?',
            'a' => 'Click "New Project" from the dashboard or project list. Follow the 6 steps: key information, context, logical framework, results, activities, finalization.',
        ],
        [
            'category' => 'projects',
            'q' => 'What is a logical framework?',
            'a' => 'The logical framework (logframe) is a matrix that structures your project: General Objective > Specific Objectives > Results > Activities. Each level can have indicators, verification sources and assumptions.',
        ],
        [
            'category' => 'projects',
            'q' => 'How to use templates?',
            'a' => 'Go to Projects > Templates. You will find pre-configured projects. Click "Use this template" to create a customizable copy. You can also mark your own projects as templates.',
        ],
        [
            'category' => 'projects',
            'q' => 'How to track project progress?',
            'a' => 'Open a project and go to the "Tracking" tab. You will see overall progress, activities by status, and a forecast vs. actual comparison.',
        ],
        [
            'category' => 'collaboration',
            'q' => 'How to invite members?',
            'a' => 'Go to Administration > Invitations. Enter the person\'s email and role. They will receive an email with a link to join your organization.',
        ],
        [
            'category' => 'collaboration',
            'q' => 'How to share a project with a donor?',
            'a' => 'Open a project, click "Share". Create a share link with an expiration date. The donor can view a read-only dashboard without needing an account.',
        ],
        [
            'category' => 'collaboration',
            'q' => 'How to comment on an activity?',
            'a' => 'Open an activity. At the bottom of the page, you will find the comments section. You can mention colleagues with @name.',
        ],
        [
            'category' => 'budget',
            'q' => 'How to track actual expenses?',
            'a' => 'Open a project, go to activities, then use the "Expenses" section to record actual expenses. The system automatically calculates the variance with planned budget and burn rate.',
        ],
        [
            'category' => 'budget',
            'q' => 'How to configure currencies?',
            'a' => 'Go to Administration > Exchange Rates. You can set manual conversion rates between currencies. Each project can have its own currency.',
        ],
        [
            'category' => 'export',
            'q' => 'What export formats are available?',
            'a' => '<strong>PDF</strong>: Professional report (modern template). <strong>Excel</strong>: 3 sheets (Activities, Budget, Indicators). <strong>Word</strong>: Editable DOCX document.',
        ],
        [
            'category' => 'export',
            'q' => 'How to export to Excel?',
            'a' => 'Open a project, click "Excel Export" in the action bar. The file contains 3 tabs: Activities, Budget and Indicators.',
        ],
        [
            'category' => 'account',
            'q' => 'How to change my password?',
            'a' => 'Go to your Profile (click your avatar at the top right). In the "Change password" section, enter your old and new password.',
        ],
        [
            'category' => 'account',
            'q' => 'How to change my avatar?',
            'a' => 'Go to your Profile. Click on your current avatar to open the selector. Choose from 24 predefined avatars.',
        ],
        [
            'category' => 'account',
            'q' => 'I am not receiving email notifications.',
            'a' => 'Check in Settings > Notifications that email notifications are enabled. Also check your spam folder. If the problem persists, contact your administrator.',
        ],
    ],

];
