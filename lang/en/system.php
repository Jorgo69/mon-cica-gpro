<?php

return [

    // ROOT Dashboard
    'root_dashboard' => [
        'title' => 'Platform Supervision',
        'subtitle' => 'Global overview of all organizations and platform activity',
        'total_organizations' => 'Total organizations',
        'total_users' => 'Total users',
        'total_projects' => 'Total projects',
        'active_users' => 'Active users',
        'storage_used' => 'Storage used',
        'system_health' => 'System health',
    ],

    // Organizations management
    'organizations' => [
        'title' => 'Organization Management',
        'subtitle' => 'Manage all registered organizations on the platform',
        'add' => 'Add an organization',
        'edit' => 'Edit organization',
        'search' => 'Search an organization...',
        'no_organizations' => 'No organizations found',
        'name' => 'Name',
        'owner' => 'Owner',
        'members_count' => 'Members',
        'projects_count' => 'Projects',
        'created_at' => 'Created on',
        'status' => 'Status',
        'impersonate' => 'View as',
        'suspend' => 'Suspend',
        'activate' => 'Activate',
        'confirm_suspend' => 'Are you sure you want to suspend this organization?',
    ],

    // Users management
    'users' => [
        'title' => 'User Management',
        'subtitle' => 'Manage all platform users',
        'add' => 'Add a user',
        'edit' => 'Edit user',
        'search' => 'Search a user...',
        'no_users' => 'No users found',
        'name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',
        'organization' => 'Organization',
        'account_type' => 'Account type',
        'last_login' => 'Last login',
        'impersonate' => 'Impersonate',
        'suspend' => 'Suspend',
        'confirm_suspend' => 'Are you sure you want to suspend this user?',
    ],

    // Global audit
    'audit' => [
        'title' => 'Global Audit',
        'subtitle' => 'Track all actions across the entire platform',
        'filter_by_org' => 'Filter by organization',
        'filter_by_user' => 'Filter by user',
        'filter_by_action' => 'Filter by action',
        'export' => 'Export logs',
    ],

    // System settings
    'settings' => [
        'title' => 'System Settings',
        'subtitle' => 'Configure global platform parameters',
        'maintenance_mode' => 'Maintenance mode',
        'maintenance_desc' => 'Enable maintenance mode to temporarily disable access to the platform.',
        'registration' => 'Registration',
        'registration_desc' => 'Allow new users to register on the platform.',
        'default_language' => 'Default language',
        'max_file_size' => 'Maximum file size',
        'allowed_file_types' => 'Allowed file types',
        'smtp_settings' => 'SMTP settings',
    ],

    // Emails
    'emails' => [
        'title' => 'Email Management',
        'subtitle' => 'View and manage platform email communications',
        'templates' => 'Email templates',
        'sent' => 'Sent emails',
        'queued' => 'Queued',
        'failed' => 'Failed',
    ],

];
