<?php

return [

    // Root dashboard
    'root_dashboard' => [
        'title' => 'Platform Supervision',
        'subtitle' => 'Global overview of all organizations and users',

        'organizations' => 'Organizations',
        'users' => 'Users',
        'projects' => 'Projects',
        'pending_invitations' => 'Pending invitations',

        'top_orgs' => [
            'organizations' => 'Organizations',
            'members' => 'Members',
            'projects' => 'Projects',
        ],

        'recent_users' => [
            'title' => 'Recent users',
            'user' => 'User',
            'role' => 'Role',
            'organization' => 'Organization',
            'date' => 'Date',
        ],

        'pending_invitations_table' => [
            'title' => 'Pending invitations',
            'email' => 'Email',
            'organization' => 'Organization',
            'invited_by' => 'Invited by',
            'date' => 'Date',
        ],

        'no_orgs' => 'No organizations registered',
        'no_users' => 'No recent users',
    ],

    // Organizations
    'organizations' => [
        'title' => 'Organization Management',
        'subtitle' => 'Manage all registered organizations on the platform',
    ],

    // Users
    'users' => [
        'title' => 'User Management',
        'subtitle' => 'Manage all platform users',
    ],

    // Emails
    'emails' => [
        'title' => 'Email Management',
        'subtitle' => 'View and manage platform email communications',
    ],

    // Roles & Permissions
    'roles' => [
        'title' => 'Role Management',
    ],

    'permissions' => [
        'title' => 'Permission Management',
    ],

    // Organization management
    'org_management' => [
        'search_placeholder' => 'Search an organization...',
        'section_title' => 'Units & Organizations',
        'name_id' => 'Name & ID',
        'contact' => 'Contact',
        'stats' => 'Statistics',
        'slug' => 'Slug',
        'no_email' => 'No email',
        'no_phone' => 'No phone',
        'members_count' => ':count Members',
        'confirm_delete_org' => 'CRITICAL ACTION: Do you really want to permanently delete this organization? All related data will be lost.',
        'no_org' => 'No organizations',
        'no_org_desc' => 'There are no organizations registered in the system yet.',
        'modal_edit_title' => 'Organization Configuration',
        'modal_create_title' => 'New Entity',
        'label_name' => 'Name',
        'placeholder_name' => 'E.g.: General Directorate',
        'label_slug' => 'Unique identifier (Slug)',
        'placeholder_slug' => 'e.g.: general-directorate',
        'label_email' => 'Professional email',
        'label_phone' => 'Phone number',
        'label_address' => 'Headquarters / Address',
        'label_description' => 'Description',
        'placeholder_description' => 'Brief description of the organization...',
        'saving' => 'Saving...',
        'save_changes' => 'Save changes',
        'create_org' => 'Create organization',
    ],

    // Role management
    'role_management' => [
        'search_placeholder' => 'Search a role...',
        'all_roles' => 'All roles',
        'global_roles' => 'System Roles (Global)',
        'section_title' => 'Roles & Access',
        'role' => 'Role',
        'scope' => 'Scope / Organization',
        'permissions' => 'Permissions',
        'org_unknown' => 'Unknown Org',
        'global_system' => 'Global System',
        'perms_count' => ':count Perms',
        'confirm_delete_role' => 'Are you sure you want to delete this role? This action is irreversible and will remove this role from all affected users.',
        'no_role' => 'No roles',
        'no_role_desc' => 'No roles match your filters.',
        'modal_create_title' => 'New System Role',
        'modal_edit_title' => 'Role Configuration',
        'label_name' => 'Role Identifier Name',
        'placeholder_name' => 'E.g.: PROJECT_MANAGER',
        'label_org' => 'Attached Organization',
        'option_global' => '-- Global System (Cross-org) --',
        'label_permissions' => 'Permission Assignment',
        'saving' => 'Saving...',
        'create_role' => 'Create role',
        'save_changes' => 'Save changes',
    ],

    // Permission management
    'perm_management' => [
        'search_placeholder' => 'Filter permissions...',
        'section_title' => 'Access Right Keys',
        'permission' => 'Permission',
        'guard' => 'Guard',
        'protection' => 'Protection',
        'critical' => 'System Critical',
        'free' => 'Free',
        'confirm_delete_perm' => 'Warning: deleting a permission may impact access to features. Confirm deletion?',
        'no_perm' => 'No permissions',
        'no_perm_desc' => 'No custom access rules have been defined yet.',
        'modal_edit_title' => 'Edit Permission',
        'modal_create_title' => 'New Access Rule',
        'label_name' => 'Identifier Name (slug)',
        'placeholder_name' => 'E.g.: report.validate',
        'hint' => 'Use dots (.) or hyphens (-) to structure your technical permission names.',
        'saving' => 'Saving...',
        'update' => 'Update',
        'add_to_system' => 'Add to system',
    ],

    // Root organization list
    'root_org_list' => [
        'title' => 'Organizations',
        'subtitle' => 'Management of all platform organizations',
        'search_placeholder' => 'Search an organization...',
        'no_org' => 'No organizations found',
        'organization' => 'Organization',
        'members' => 'Members',
        'projects' => 'Projects',
        'enter' => 'Enter',
        'suspend' => 'Suspend',
        'activate' => 'Activate',
        'confirm_suspend' => 'Suspend :name?',
    ],

    // Root user list
    'root_user_list' => [
        'search_placeholder' => 'Search by name or email...',
        'all_orgs' => 'All organizations',
        'all_roles' => 'All roles',
        'section_title' => 'Global users',
        'user' => 'User',
        'organization' => 'Organization',
        'no_org' => 'None',
        'unknown' => 'Unknown',
        'verified' => 'Verified',
        'blocked' => 'Blocked',
        'confirm_block' => 'Block this user?',
        'confirm_unblock' => 'Unblock this user?',
        'confirm_reset' => 'Send a password reset link to :email?',
        'no_user' => 'No users',
        'no_user_desc' => 'No users match the search criteria.',
    ],

    // Root email suppression
    'email_suppression' => [
        'title' => 'Emails - Suppression List',
        'description' => 'Manage blocked emails (bounce, unsubscribe, complaint).',
        'bounced' => 'Bounced',
        'unsubscribed' => 'Unsubscribed',
        'complained' => 'Complaint',
        'search_placeholder' => 'Search an email...',
        'all_reasons' => 'All reasons',
        'section_title' => 'Suppressed emails',
        'email' => 'Email',
        'reason' => 'Reason',
        'details' => 'Details',
        'confirm_remove' => 'Remove this email from the list?',
        'remove' => 'Remove',
        'no_email' => 'No suppressed emails',
        'no_email_desc' => 'The suppression list is empty.',
    ],

    // Audit / Activity history
    'audit' => [
        'title' => 'Activity History',
        'subtitle' => 'Track all actions across the entire platform',
        'total_events' => 'Total: :count events',
        'system' => 'System',
        'initial_data' => 'Initial data recorded.',
        'no_activity' => 'No activity recorded yet.',
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

];
