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
        'no_members' => 'No members found',
        'role' => 'Role',
        'joined_at' => 'Joined on',
        'last_active' => 'Last active',
        'confirm_remove' => 'Are you sure you want to remove this member from the organization?',
        'change_role' => 'Change role',
    ],

    // Categories
    'categories' => [
        'title' => 'Category Management',
        'subtitle' => 'Organize your projects and resources by category',
        'add' => 'Add a category',
        'edit' => 'Edit category',
        'name' => 'Category name',
        'description' => 'Description',
        'color' => 'Color',
        'icon' => 'Icon',
        'parent' => 'Parent category',
        'no_categories' => 'No categories yet',
        'confirm_delete' => 'Are you sure you want to delete this category?',
    ],

    // Invitations
    'invitations' => [
        'title' => 'Invitations',
        'subtitle' => 'Manage invitations sent to join your organization',
        'send' => 'Send an invitation',
        'resend' => 'Resend',
        'revoke' => 'Revoke',
        'email' => 'Email address',
        'role' => 'Role',
        'status' => 'Status',
        'sent_at' => 'Sent on',
        'expires_at' => 'Expires on',
        'no_invitations' => 'No invitations sent',
        'pending' => 'Pending',
        'accepted' => 'Accepted',
        'expired' => 'Expired',
        'revoked' => 'Revoked',
        'confirm_revoke' => 'Are you sure you want to revoke this invitation?',
        'invitation_sent' => 'Invitation sent successfully.',
        'invitation_resent' => 'Invitation resent successfully.',
        'invitation_revoked' => 'Invitation revoked.',
    ],

    // Trash
    'trash' => [
        'title' => 'Trash',
        'subtitle' => 'Deleted items that can be restored',
        'restore' => 'Restore',
        'delete_permanently' => 'Delete permanently',
        'empty_trash' => 'Empty trash',
        'no_items' => 'Trash is empty',
        'deleted_at' => 'Deleted on',
        'deleted_by' => 'Deleted by',
        'confirm_permanent_delete' => 'Are you sure? This action is irreversible.',
        'confirm_empty_trash' => 'Are you sure you want to permanently delete all items in the trash?',
        'item_restored' => 'Item restored successfully.',
        'item_deleted' => 'Item permanently deleted.',
    ],

    // Project types
    'project_types' => [
        'title' => 'Project Types',
        'subtitle' => 'Configure the types of projects available in your organization',
        'add' => 'Add a project type',
        'edit' => 'Edit project type',
        'name' => 'Type name',
        'description' => 'Description',
        'fields' => 'Dynamic fields',
        'no_types' => 'No project types configured',
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
