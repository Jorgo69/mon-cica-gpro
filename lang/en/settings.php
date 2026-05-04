<?php

return [

    'title' => 'Settings',
    'subtitle' => 'Customize your experience according to your preferences',

    // Tabs
    'appearance' => 'Appearance',
    'language' => 'Language',
    'notifications' => 'Notifications',
    'linked_accounts' => 'Linked accounts',
    'organization' => 'Organization',

    // Organization Profile
    'org_profile' => 'Organization Profile',
    'org_logo' => 'Logo',
    'upload_logo' => 'Change logo',
    'remove_logo' => 'Remove logo',
    'org_name' => 'Organization name',
    'org_description' => 'Description',
    'org_description_placeholder' => 'Briefly describe your organization (visible in emails and shared dashboards)...',
    'org_website' => 'Website',
    'org_contact_email' => 'Contact email',
    'org_contact_phone' => 'Phone',
    'org_branding_hint' => 'This information will appear in invitation emails and dashboards shared with your partners.',

    // Ownership transfer
    'transfer' => [
        'title' => 'Organization Ownership',
        'you_are_owner' => 'You are the owner of this organization.',
        'desc' => 'The owner has full control: cannot be removed or demoted. You can transfer this role to another administrator.',
        'transfer_btn' => 'Transfer ownership',
        'no_other_admin' => 'Add another administrator first to be able to transfer ownership.',
        'modal_title' => 'Transfer ownership',
        'warning' => 'Warning: after the transfer, you will remain an administrator but will no longer be the owner. This action is only reversible by the new owner.',
        'select_admin' => 'New owner administrator',
        'choose' => 'Choose an administrator',
        'confirm' => 'Transfer',
        'success' => 'Ownership transferred to :name.',
        'not_owner' => 'You are not the owner of this organization.',
        'select_target' => 'Please select an administrator.',
        'target_not_admin' => 'The target must be an administrator of your organization.',
    ],

    // Theme
    'theme' => [
        'title' => 'Interface theme',
        'desc' => 'Switch between light and dark mode',
        'light' => 'Light',
        'dark' => 'Dark',
    ],

    // Density
    'density' => [
        'title' => 'Display density',
        'compact' => 'Compact',
        'comfortable' => 'Comfortable',
        'spacious' => 'Spacious',
        'compact_desc' => 'Condensed display for more visible content',
        'comfortable_desc' => 'Balanced spacing for reading comfort',
        'spacious_desc' => 'Generous spacing for relaxed reading',
    ],

    // Language
    'language_settings' => [
        'title' => 'Language and region',
        'french' => 'Français',
        'english' => 'English',
    ],

    // Date format
    'date_format' => [
        'title' => 'Date format',
        'french' => 'DD/MM/YYYY',
        'american' => 'MM/DD/YYYY',
        'iso' => 'YYYY-MM-DD',
    ],

    // Email notifications
    'email_notifications' => [
        'title' => 'Email notifications',
        'desc' => 'Receive email notifications for important events',
    ],

    // Timezone
    'timezone' => [
        'title' => 'Timezone',
        'desc' => 'Set your timezone for date and time display',
    ],

    // Profile
    'profile' => [
        'title' => 'My Profile',
        'subtitle' => 'Manage your personal information',

        'avatar' => 'Profile picture',
        'change_avatar' => 'Change picture',
        'remove_avatar' => 'Remove picture',
        'choose_avatar' => 'Choose a picture',

        'personal_info' => 'Personal information',
        'full_name' => 'Full name',
        'email' => 'Email address',
        'phone' => 'Phone number',
        'sex' => 'Gender',
        'country' => 'Country',
        'city' => 'City',
        'city_placeholder' => 'Type your city name...',

        'saved' => 'Profile updated',

        'password_title' => 'Change password',
        'current_password' => 'Current password',
        'new_password' => 'New password',
        'confirm_password' => 'Confirm password',

        'danger_zone' => 'Danger zone',
        'delete_account' => 'Delete my account',
        'delete_account_desc' => 'Deleting your account is irreversible. All your data will be permanently erased.',
        'delete_account_confirm' => 'Are you sure you want to delete your account?',
    ],

    // GDPR / Danger zone
    'delete' => [
        'danger_title' => 'Danger Zone',
        'export_title' => 'Export my data',
        'export_desc' => 'Download a copy of all your personal data in JSON format.',
        'export_btn' => 'Download my data',
        'export_org_title' => 'Export organization data',
        'export_org_desc' => 'Download a full copy of your organization data (members, projects, budgets).',
        'export_org_btn' => 'Download org data',
        'delete_account_title' => 'Delete my account',
        'delete_account_desc' => 'Your account will be anonymized and your personal data deleted. This action is irreversible.',
        'delete_account_btn' => 'Delete my account',
        'delete_org_title' => 'Delete organization',
        'delete_org_desc' => 'The organization will be deleted after a 30-day grace period. All members will be detached and projects archived. You can cancel during this period.',
        'delete_org_btn' => 'Schedule deletion',
        'delete_org_cancel' => 'Cancel deletion',
        'confirm_password' => 'Confirm your password to continue',
        'wrong_password' => 'Incorrect password.',
        'must_transfer_ownership' => 'You must first transfer organization ownership to another admin before deleting your account.',
        'not_owner' => 'Only the organization owner can perform this action.',
        'deleted_user' => 'Deleted user',
        'org_scheduled' => 'Deletion scheduled. The organization will be deleted in 30 days. An email was sent to all members.',
        'org_cancelled' => 'Deletion cancelled. The organization will not be deleted.',
        'org_scheduled_at' => 'Deletion scheduled for :date',
        'org_scheduled_warning' => 'This organization is scheduled for deletion. You can cancel this action.',
    ],

    // Independent → Organization
    'org_create' => [
        'title' => 'Create my organization',
        'desc' => 'You are currently in independent mode. Create your organization to invite members and collaborate.',
        'name_label' => 'Organization name',
        'name_placeholder' => 'E.g.: My NGO',
        'info' => 'Your existing projects will be automatically migrated to your new organization. You will become administrator.',
        'submit' => 'Create organization',
        'success' => 'Organization ":org" created! You are now administrator.',
        'not_independent' => 'Only independent users can create an organization.',
    ],

    // API tokens
    'api' => [
        'title' => 'API Tokens',
        'desc' => 'Generate tokens to access the REST API from your external applications.',
        'token_name_placeholder' => 'Token name (e.g.: My App)',
        'create_token' => 'Create token',
        'token_created' => 'Token created successfully.',
        'token_warning' => 'Copy this token now. It won\'t be shown again.',
        'token_revoked' => 'Token revoked.',
        'confirm_revoke' => 'Revoke this token? Applications using it will lose access.',
        'revoke' => 'Revoke',
        'created' => 'Created',
        'last_used' => 'Last used',
        'no_tokens' => 'No API tokens. Create one to get started.',
    ],

    // Webhooks
    'webhooks' => [
        'title' => 'Webhooks',
        'desc' => 'Receive real-time notifications in your external applications.',
        'url' => 'Webhook URL',
        'label' => 'Label (optional)',
        'label_placeholder' => 'E.g.: My Slack integration',
        'events' => 'Events',
        'add' => 'Add webhook',
        'saved' => 'Webhook saved.',
        'deleted' => 'Webhook deleted.',
        'confirm_delete' => 'Delete this webhook?',
        'no_webhooks' => 'No webhooks configured.',
        'failures' => 'consecutive failure(s)',
    ],

];
