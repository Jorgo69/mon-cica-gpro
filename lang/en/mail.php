<?php

return [

    'greeting' => 'Hello :name,',
    'greeting_simple' => 'Hello,',
    'salutation' => '— :app',
    'view_project' => 'View project',
    'view_dashboard' => 'View dashboard',
    'unsubscribe' => 'Unsubscribe',
    'someone' => 'Someone',
    'an_admin' => 'An administrator',

    // ActivityAssignedNotification
    'activity_assigned' => [
        'subject' => 'Activity assigned: :activity',
        'greeting' => 'Hello :name,',
        'line1' => '**:assigner** assigned you an activity on the project **:project**.',
        'line2' => 'Activity: **:activity**',
        'action' => 'View project',
        'title' => 'Activity assigned',
        'message' => ':assigner assigned you the activity ":activity".',
    ],

    // ActivityProgressUpdatedNotification
    'activity_progress_updated' => [
        'subject' => 'Activity updated — :progress%',
        'line1' => 'The activity **:activity** on project **:project** has reached **:progress%** completion.',
        'action' => 'View project',
        'title' => 'Activity Progress Updated',
        'message' => 'The activity ":activity" has reached :progress% completion.',
    ],

    // ProjectStatusUpdatedNotification
    'project_status_updated' => [
        'subject' => 'Project ":project" — Status changed',
        'line1' => 'The status of project **:project** has been changed.',
        'line2' => '**:old_status** → **:new_status**',
        'action' => 'View project',
        'title' => 'Project Status Changed',
        'message' => 'Project ":project" changed from :old_status to :new_status.',
    ],

    // ProjectSubmittedNotification
    'project_submitted' => [
        'subject' => 'New project submitted: :project',
        'line1' => '**:submitter** submitted the project **:project** for validation.',
        'line2' => 'Project code: :code',
        'action' => 'View project',
        'title' => 'New project submitted',
        'message' => ':submitter submitted the project ":project" for validation.',
    ],

    // InvitationNotification
    'invitation' => [
        'subject' => 'Invitation to join :organization',
        'line1' => '**:sender** invites you to join the **:organization** workspace on :app.',
        'line2' => 'Click the button below to accept the invitation:',
        'action' => 'Accept invitation',
        'line3' => 'You can also use this invitation code: **:code**',
        'line4' => 'This code is valid for 7 days.',
    ],

    // CommentPostedNotification
    'comment_posted' => [
        'subject_mention' => ':author mentioned you in a comment',
        'subject_comment' => ':author commented on an activity',
        'action' => 'View',
        'title_mention' => 'You were mentioned',
        'title_comment' => 'New comment',
        'message_mention' => ':author mentioned you: ":excerpt"',
        'message_comment' => ':author commented: ":excerpt"',
    ],

    // ActivityOverdueNotification
    'activity_overdue' => [
        'subject_escalation' => '[ESCALATION] Activity overdue by :days days',
        'subject_normal' => 'Activity overdue: :activity',
        'line_escalation1' => '**ESCALATION** — The activity **:activity** on project **:project** has been overdue for **:days days**.',
        'line_escalation2' => 'The responsible person (:responsible) has not updated this activity. Your intervention is required.',
        'line_normal1' => 'The activity **:activity** on project **:project** is overdue by **:days day(s)**.',
        'line_normal2' => 'Deadline exceeded: **:date**',
        'action' => 'View project',
        'title_escalation' => '[ESCALATION] Activity overdue (:daysd)',
        'title_normal' => 'Activity overdue (:daysd)',
        'message_escalation' => '[ESCALATION] The activity ":activity" is overdue by :days day(s).',
        'message_normal' => 'The activity ":activity" is overdue by :days day(s).',
    ],

    // BudgetThresholdNotification
    'budget_threshold' => [
        'level_exceeded' => 'exceeded',
        'level_reached' => 'reached :percent%',
        'subject' => 'Budget :level — :project',
        'line1' => 'The budget of project **:project** has **:level**.',
        'action' => 'View project',
        'title' => 'Budget :level',
        'message' => 'The budget of project ":project" has :level.',
    ],

    // DeadlineApproachingNotification
    'deadline_approaching' => [
        'label_tomorrow' => 'tomorrow',
        'label_days' => 'in :days days',
        'subject' => 'Deadline :label: :activity',
        'line1' => 'The activity **:activity** on project **:project** is due **:label**.',
        'line2' => 'Deadline: **:date**',
        'action' => 'View project',
        'title' => 'Deadline :label',
        'message' => 'The activity ":activity" is due :label.',
    ],

    // WeeklyDigestNotification
    'weekly_digest' => [
        'subject' => 'Weekly digest — :app',
        'line_intro' => 'Here is your weekly summary:',
        'overdue' => '**:count** overdue activity(ies)',
        'upcoming' => '**:count** deadline(s) this week',
        'completed' => '**:count** activity(ies) completed this week',
        'projects' => '**:count** active project(s)',
        'action' => 'View dashboard',
    ],
];
