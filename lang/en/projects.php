<?php

return [

    'title' => 'Project List',
    'subtitle' => 'Manage and track the progress of your strategic initiatives',

    // Actions
    'new_project' => 'New project',
    'search_project' => 'Search a project...',
    'no_projects_found' => 'No projects found for this selection',
    'all_responsibles' => 'All responsibles',
    'all_statuses' => 'All statuses',

    // Table
    'table' => [
        'title' => 'Title',
        'code' => 'Code',
        'status' => 'Status',
        'responsible' => 'Responsible',
        'start' => 'Start',
        'end' => 'End',
        'preview' => 'Preview',
        'update' => 'Edit',
        'delete' => 'Delete',
        'budget' => 'Budget',
        'progress' => 'Progress',
        'manage' => 'Manage',
        'description' => 'Description',
    ],

    // Creation wizard
    'step_1' => [
        'title' => 'Key information',
        'description' => 'Basic project details',
        'form' => [
            'title' => 'Key project information',
            'description' => 'Enter basic administrative details and choose the project type',
            'input_1' => 'Project type',
            'option' => 'Select a project type',
            'select' => 'Project type description',
            'input_2' => 'Project title',
            'input_3' => 'Project code',
            'input_4' => 'Short title (optional)',
            'input_5' => 'Start date',
            'input_6' => 'End date',
        ],
        'type_message' => 'No dynamic fields configured for this project type',
    ],
    'step_2' => [
        'title' => 'Background & documents',
        'description' => 'Description and relevant files',
    ],
    'step_3' => [
        'title' => 'Logical framework',
        'description' => 'Overall goal and specific objectives',
    ],
    'step_4' => [
        'title' => 'Expected results',
        'description' => 'Concrete project deliverables',
    ],
    'step_5' => [
        'title' => 'Activities',
        'description' => 'Preliminary actions',
    ],
    'step_6' => [
        'title' => 'Finalization',
        'description' => 'Verification and submission',
    ],

    // Show page
    'show' => [
        'title' => 'Project Details',
        'overview' => 'Overview',
        'logical_framework' => 'Logical Framework',
        'activities' => 'Activities',
        'budget' => 'Budget',
        'documents' => 'Documents',
        'team' => 'Team',
        'timeline' => 'Timeline',
        'general_objective' => 'General Objective',
        'specific_objectives' => 'Specific Objectives',
        'expected_results' => 'Expected Results',
        'indicators' => 'Indicators',
        'assumptions' => 'Assumptions',
        'verification_sources' => 'Verification Sources',
        'no_objectives' => 'No objectives defined yet',
        'no_results' => 'No results defined yet',
        'no_activities' => 'No activities defined yet',
        'project_info' => 'Project Information',
        'start_date' => 'Start date',
        'end_date' => 'End date',
        'duration' => 'Duration',
        'status' => 'Status',
        'responsible' => 'Responsible',
        'type' => 'Type',
        'code' => 'Code',
    ],

    // Dashboard
    'dashboard' => [
        'title' => 'Project Dashboard',
        'progress' => 'Progress',
        'budget_overview' => 'Budget Overview',
        'activity_summary' => 'Activity Summary',
        'total_activities' => 'Total activities',
        'completed_activities' => 'Completed activities',
        'in_progress_activities' => 'Activities in progress',
        'overdue_activities' => 'Overdue activities',
        'pending_activities' => 'Pending activities',
        'completion_rate' => 'Completion rate',
    ],

    // Design
    'design' => [
        'title' => 'Project Design',
        'context' => 'Context',
        'problematic' => 'Problematic',
        'justification' => 'Justification',
        'beneficiaries' => 'Beneficiaries',
        'direct_beneficiaries' => 'Direct beneficiaries',
        'indirect_beneficiaries' => 'Indirect beneficiaries',
        'coverage_area' => 'Coverage area',
        'intervention_strategy' => 'Intervention strategy',
    ],

    // Proposal
    'proposal' => [
        'title' => 'Project Proposal',
        'generate' => 'Generate proposal',
        'download_pdf' => 'Download PDF',
        'download_docx' => 'Download DOCX',
        'cover_page' => 'Cover page',
        'table_of_contents' => 'Table of contents',
        'executive_summary' => 'Executive summary',
    ],

];
