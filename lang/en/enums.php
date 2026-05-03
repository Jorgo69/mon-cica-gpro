<?php

return [

    // ActivityStatus
    'activity_status' => [
        'draft' => 'Draft',
        'abandoned' => 'Abandoned',
        'stopped' => 'Stopped',
        'pending' => 'Pending',
        'ongoing' => 'Ongoing',
        'suspended' => 'Suspended',
        'completed' => 'Completed',
        'overdue' => 'Overdue',
    ],

    // ProjectStatus
    'project_status' => [
        'draft' => 'Draft',
        'pending' => 'Pending',
        'active' => 'Active',
        'on_hold' => 'On hold',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    // AccountType
    'account_type' => [
        'root' => 'Super Administrator',
        'org_admin' => 'Workspace Administrator',
        'org_user' => 'Collaborator',
        'independent' => 'Independent',
    ],

    // InvitationStatus
    'invitation_status' => [
        'pending' => 'Pending',
        'accepted' => 'Accepted',
        'expired' => 'Expired',
        'revoked' => 'Revoked',
    ],

    // AdminCategoryType
    'admin_category_type' => [
        'project_status' => 'Project status',
        'activity_status' => 'Activity status',
        'project_category' => 'Project category',
        'budget_category' => 'Budget category',
        'resource_type' => 'Resource type',
        'document_type' => 'Document type',
    ],

    // NotificationType
    'notification_type' => [
        'project_submitted' => 'Project submitted',
        'project_status_updated' => 'Project status updated',
        'activity_assigned' => 'Activity assigned',
        'activity_progress_updated' => 'Activity progress updated',
        'deadline_approaching' => 'Deadline approaching',
        'activity_overdue' => 'Activity overdue',
        'budget_threshold' => 'Budget threshold',
        'weekly_digest' => 'Weekly digest',
        'invitation' => 'Invitation',
    ],

    // LogframeDisplayFormat
    'logframe_format' => [
        'table' => 'Table',
        'tree' => 'Tree',
        'cards' => 'Cards',
    ],

    // OrganizationStatus
    'organization_status' => [
        'trial' => 'Trial',
        'active' => 'Active',
        'suspended' => 'Suspended',
        'inactive' => 'Inactive',
    ],

    // Currency
    'currency' => [
        'XOF' => 'CFA Franc (BCEAO)',
        'XAF' => 'CFA Franc (BEAC)',
        'EUR' => 'Euro',
        'USD' => 'US Dollar',
        'GBP' => 'British Pound',
        'CHF' => 'Swiss Franc',
        'CAD' => 'Canadian Dollar',
        'NGN' => 'Naira',
    ],

    // AI Provider
    'ai_provider' => [
        'custom' => 'Custom (URL + key)',
    ],

    // Permission Level
    'permission_level' => [
        'OBSERVER' => 'Observer',
        'CONTRIBUTOR' => 'Contributor',
        'MANAGER' => 'Manager',
        'ADMIN' => 'Administrator',
    ],
    'permission_level_desc' => [
        'OBSERVER' => 'Read-only: view projects, budgets and indicators.',
        'CONTRIBUTOR' => 'Create projects, edit activities, track progress.',
        'MANAGER' => 'Full project management: activities, budgets, deletion.',
        'ADMIN' => 'Full access: members, settings, invitations, organization.',
    ],
];
