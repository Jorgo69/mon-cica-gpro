<?php

return [

    // Dashboard
    'dashboard_overview' => 'This dashboard shows overall statistics for your projects. Use the time filters to refine the period.',

    // Projects
    'project_code' => 'The project code is a unique identifier generated automatically. You can customize it.',
    'project_type' => 'The project type determines the available dynamic fields and the logical framework structure.',
    'project_status' => '<strong>Draft</strong>: in preparation. <strong>Active</strong>: ongoing. <strong>Completed</strong>: closed.',
    'logframe' => 'The logical framework is a hierarchy: General Objective &gt; Specific Objectives &gt; Results &gt; Activities. Each level can have indicators.',
    'logframe_indicators' => 'Indicators measure progress. Define a baseline value, a target, and record measurements over time.',

    // Activities
    'activity_progress' => 'Progress is a percentage from 0 to 100%. It is updated manually or via sub-activities.',
    'activity_responsible' => 'The responsible person is in charge of this activity. They will receive reminders and notifications.',
    'sub_activities' => 'Sub-activities break down an activity into smaller tasks. Their average progress calculates the parent activity progress.',

    // Budget
    'budget_planned' => 'The planned budget corresponds to initial forecasts per budget line.',
    'budget_real' => 'Actual expenses are entered as they occur. The variance with planned is calculated automatically.',
    'budget_burn_rate' => 'The burn rate projects expenses until the end of the project based on the current spending pace.',

    // Templates
    'templates' => 'Templates are reusable pre-configured projects. Mark an existing project as a template for it to appear here.',

    // Share
    'share_link' => 'Share links allow external people (donors, partners) to view the project without having an account. Set an expiration date for security.',

    // Timeline
    'timeline_gantt' => 'The Gantt chart displays activities on a timeline. Bars are colored by status. The red line indicates today\'s date.',

    // Indicators
    'indicator_tracking' => 'Track your indicators by adding regular measurements. The trend arrow shows recent evolution.',
    'indicator_baseline' => 'The baseline value is the initial situation before the project starts.',
    'indicator_target' => 'The target is the value to achieve by the end of the project.',

    // Settings
    'settings_theme' => 'The theme changes the visual appearance. Dark mode reduces eye strain.',
    'settings_notifications' => 'Configure which notifications you want to receive by email, push, or in-app.',

    // Admin
    'admin_categories' => 'Categories organize your data by type (project status, budget category, resource type, etc.).',
    'admin_invitations' => 'Invite collaborators by email. They will receive a link to join your organization.',
    'admin_roles' => 'Roles define permissions. ORG_ADMIN manages the organization, MANAGER manages projects, MEMBER participates.',

    // AI
    'ai_button' => 'Click the AI button to auto-generate content. You can apply, regenerate or dismiss the suggestion.',
    'ai_provider' => 'The provider is the AI service used. Groq is recommended (free, fast). You can change at any time.',
    'ai_api_key' => 'The API key is provided by the provider. It is encrypted and secured. You can find it in the provider\'s console.',
    'ai_model' => 'The model is the AI version. Leave empty to use the default model (recommended).',
    'ai_toggle_member' => 'Disable AI for a member if you don\'t want them using your API key credits.',

    // FAQ link
    'need_more_help' => 'Need help? Check the <a href="/faq" class="text-accent underline">FAQ</a>.',

];
