<?php

return [

    // Members
    'members' => [
        'title' => 'Member Management',
        'subtitle' => 'Manage your organization\'s team members',
        'add' => 'Add a member',
        'edit' => 'Edit member',
        'remove' => 'Remove member',
        'search' => 'Search a member...',
        'search_placeholder' => 'Search a member...',
        'section' => 'Members',

        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'role' => 'Role',
        'department' => 'Department',
        'country' => 'Country',
        'city' => 'City',
        'actions' => 'Actions',
        'joined_at' => 'Joined on',
        'last_active' => 'Last active',
        'permission_level' => 'Permission level',
        'customize_permissions' => 'Customize permissions',
        'permissions_list' => 'Permissions',
        'save_permissions' => 'Save permissions',
        'permissions_updated' => 'Permissions updated.',

        'no_members' => 'No members found',
        'no_members_desc' => 'No members found for this organization.',
        'not_specified' => 'Not specified',

        'modal_add' => 'Add a member',
        'modal_edit' => 'Edit member',
        'modal_details' => 'Member details',
        'modal_confirm_delete' => 'Confirm deletion',

        'confirm_remove' => 'Are you sure you want to remove this member from the organization?',
        'confirm_delete_text' => 'Do you really want to delete :name? This action is irreversible.',
        'delete_permanently' => 'Delete permanently',
        'delete_confirm_title' => 'Delete member?',
        'change_role' => 'Change role',
    ],

    // Categories
    'categories' => [
        'title' => 'Category Management',
        'subtitle' => 'Organize your projects and resources by category',
        'new' => 'New category',
        'add' => 'Add a category',
        'edit' => 'Edit category',

        'search_placeholder' => 'Search a category...',
        'all_types' => 'All types',

        'name' => 'Name',
        'type' => 'Type',
        'description' => 'Description',
        'source' => 'Source',
        'color' => 'Color',
        'icon' => 'Icon',
        'parent' => 'Parent category',
        'actions' => 'Actions',

        'no_categories' => 'No categories yet',
        'no_categories_desc' => 'No categories found.',
        'no_categories_detail' => 'Create your first category or adjust your filters.',

        'modal_edit' => 'Edit category',
        'modal_new' => 'New category',

        'system' => 'System',
        'organization' => 'Organization',
        'protected' => 'Protected',

        'confirm_delete' => 'Are you sure you want to delete this category?',

        'type_label' => 'Category type',
        'select_type' => 'Select a type',
        'name_label' => 'Category name',
        'name_placeholder' => 'E.g.: Personnel, Infrastructure...',
        'desc_placeholder' => 'Describe this category...',
        'update' => 'Update',
        'create' => 'Create category',
        'section' => 'Categories',
    ],

    // Invitations
    'invitations' => [
        'title' => 'Invitations',
        'subtitle' => 'Manage invitations sent to join your organization',
        'invite' => 'Invite',
        'invite_member' => 'Invite a member',

        'search_placeholder' => 'Search an invitation...',
        'search_by_email' => 'Search by email...',
        'all_statuses' => 'All statuses',

        'email' => 'Email address',
        'email_placeholder' => 'collaborator@example.com',
        'organization' => 'Organization',
        'role' => 'Role',
        'status' => 'Status',
        'invited_by' => 'Invited by',
        'sent_at' => 'Sent on',
        'expires_at' => 'Expires on',
        'code' => 'Code',
        'actions' => 'Actions',
        'target_organization' => 'Target organization',
        'select_organization' => 'Select an organization',
        'account_type' => 'Account type',

        'send' => 'Send invitation',
        'resend' => 'Resend',
        'revoke' => 'Revoke',

        'no_invitations' => 'No invitations sent',
        'no_invitations_desc' => 'No pending invitations.',
        'no_invitations_detail' => 'Invite members to join your organization.',

        'confirm_resend' => 'Resend a new invitation?',
        'confirm_revoke' => 'Are you sure you want to revoke this invitation?',
        'cooldown' => 'Please wait :minutes minute(s) before resending.',
        'cooldown_hint' => 'Anti-spam cooldown active',

        'pending' => 'Pending',
        'accepted' => 'Accepted',
        'expired' => 'Expired',
        'revoked' => 'Revoked',

        'invitation_sent' => 'Invitation sent successfully.',
        'invitation_resent' => 'Invitation resent successfully.',
        'invitation_revoked' => 'Invitation revoked.',

        'roles' => [
            'member' => 'Member',
            'manager' => 'Manager',
            'admin' => 'Administrator',
            'org_admin' => 'Administrator',
            'supervisor' => 'Supervisor',
        ],
        'role_desc' => [
            'member' => 'Can view projects and track their activities.',
            'manager' => 'Can create and manage projects, assign activities.',
            'admin' => 'Full access: member management, projects, organization settings.',
        ],
    ],

    // Project types
    'types' => [
        'title' => 'Project Types',
        'subtitle' => 'Configure project types and their custom fields',
        'new' => 'New type',

        'list_title' => 'Project Types',
        'list_subtitle' => 'Configure categories and dynamic fields for your projects',
        'create_new' => 'Create a new type',
        'no_types' => 'No project types',
        'no_types_desc' => 'Create your first project type to get started.',
        'confirm_delete' => 'Are you sure you want to delete this project type?',

        'edit_title' => 'Edit project type',
        'new_title' => 'New project type',
        'form_subtitle' => 'Configure information and dynamic fields',

        'key_info' => 'Key Information',
        'name' => 'Name',
        'name_label' => 'Project type name',
        'name_placeholder' => 'E.g.: Development Project',
        'description' => 'Description',
        'category' => 'Category',
        'no_category' => 'No category',
        'fields' => 'Fields',
        'projects' => 'Projects',
        'actions' => 'Actions',

        'dynamic_fields' => 'Dynamic Fields',
        'question_label' => 'Question label',
        'field_type' => 'Field type',
        'field_name' => 'Field name',
        'render_type' => 'Render type',
        'order' => 'Order',
        'target_field' => 'Target field',
        'target_placeholder' => 'E.g.: title',
        'section' => 'Section',
        'section_placeholder' => 'E.g.: Basic information',
        'required' => 'Required',
        'add_field' => 'Add a field',

        'type_text' => 'Text (simple)',
        'type_textarea' => 'Text area (long)',
        'type_select' => 'Dropdown',
        'type_date' => 'Date',
        'type_number' => 'Number',
        'render_select' => 'Dropdown',
        'render_radio' => 'Radio buttons',
        'render_checkbox' => 'Checkboxes',

        'options_title' => 'List options',
        'option_label' => 'Label',
        'option_value' => 'Value',
        'add_option' => 'Add an option',

        'save' => 'Save',
    ],

    // Trash
    'trash' => [
        'title' => 'Trash',
        'subtitle' => 'Manage deleted items — restore or permanently delete',

        'members' => 'Members',
        'members_section' => 'Deleted members',
        'project_types' => 'Project types',
        'project_types_section' => 'Deleted project types',
        'projects' => 'Projects',
        'projects_section' => 'Deleted projects',

        'name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',
        'deleted_at' => 'Deleted on',
        'actions' => 'Actions',
        'category' => 'Category',
        'title_col' => 'Title',
        'code' => 'Code',
        'status' => 'Status',

        'no_members' => 'No deleted members',
        'no_types' => 'No deleted types',
        'no_projects' => 'No deleted projects',
        'no_items' => 'Trash is empty',

        'restore' => 'Restore',
        'delete_permanently' => 'Delete permanently',
        'empty_trash' => 'Empty trash',
        'view_details' => 'Item details',
        'permanent_delete' => 'Permanent deletion',
        'irreversible' => 'This action is irreversible.',
        'confirm_permanent_delete' => 'Do you really want to permanently delete this item?',
        'confirm_empty_trash' => 'Are you sure you want to permanently delete all items in the trash?',
        'item_restored' => 'Item restored successfully.',
        'item_deleted' => 'Item permanently deleted.',
    ],

    // Projects (admin list)
    'projects' => [
        'title' => 'All Projects',
        'subtitle' => 'Admin view of all system projects',
        'section' => 'Projects',

        'search_placeholder' => 'Search a project...',
        'all_statuses' => 'All statuses',
        'all_responsibles' => 'All responsibles',

        'title_col' => 'Title',
        'code' => 'Code',
        'status' => 'Status',
        'responsible' => 'Responsible',
        'start_date' => 'Start',
        'end_date' => 'End',
        'actions' => 'Actions',

        'no_projects' => 'No projects found',
        'no_projects_desc' => 'Adjust your filters or wait for projects to be created.',
    ],

    // Roles & Permissions
    'roles' => [
        'title' => 'Roles & Permissions',
        'subtitle' => 'Configure access roles and their permissions',
        'add' => 'Add a role',
        'edit' => 'Edit role',
        'name' => 'Role name',
        'description' => 'Description',
        'permissions' => 'Permissions',
        'users_count' => 'Number of users',
        'no_roles' => 'No roles configured',
    ],

    // Exchange rates
    'exchange_rates' => [
        'title' => 'Exchange Rates',
        'subtitle' => 'Configure currency conversion rates',
        'add' => 'Add a rate',
        'edit' => 'Edit rate',
        'base_currency' => 'Base currency',
        'target_currency' => 'Target currency',
        'rate' => 'Rate',
        'effective_date' => 'Effective date',
        'no_rates' => 'No exchange rates configured',
    ],

    // Audit
    'audit' => [
        'title' => 'Audit Log',
        'subtitle' => 'Track all actions performed in the organization',
        'user' => 'User',
        'action' => 'Action',
        'model' => 'Entity',
        'date' => 'Date',
        'details' => 'Details',
        'old_values' => 'Old values',
        'new_values' => 'New values',
        'no_logs' => 'No audit logs',
        'created' => 'Created',
        'updated' => 'Updated',
        'deleted' => 'Deleted',
    ],

];
