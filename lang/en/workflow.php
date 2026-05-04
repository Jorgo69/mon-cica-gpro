<?php

return [

    'title' => 'Workflow',
    'history' => 'Approval history',
    'comment_placeholder' => 'Comment (optional)...',
    'confirm_transition' => 'Change status to ":status"?',
    'transition_success' => 'Status changed to ":status".',

    'invalid_transition' => 'Cannot transition from ":from" to ":to".',
    'only_creator_can_submit' => 'Only the project creator can submit it.',
    'only_manager_can_review' => 'Only managers and administrators can review/approve.',
    'only_admin_can_activate' => 'Only an administrator can activate a project.',
    'unauthorized' => 'You are not authorized to perform this action.',

    'actions' => [
        'submit' => 'submitted the project',
        'review' => 'started reviewing',
        'approve' => 'approved the project',
        'reject' => 'rejected the project',
        'activate' => 'activated the project',
        'revert_to_draft' => 'reverted to draft',
        'transition' => 'changed the status',
    ],

    'mail' => [
        'view_project' => 'View project',
        'comment' => 'Comment',
        'submit_subject' => 'Project submitted: :project',
        'submit_line' => ':actor submitted the project ":project" for validation.',
        'review_subject' => 'Project under review: :project',
        'review_line' => ':actor started reviewing the project ":project".',
        'approve_subject' => 'Project approved: :project',
        'approve_line' => ':actor approved the project ":project".',
        'reject_subject' => 'Project rejected: :project',
        'reject_line' => ':actor rejected the project ":project".',
        'activate_subject' => 'Project activated: :project',
        'activate_line' => ':actor activated the project ":project".',
        'revert_to_draft_subject' => 'Project reverted to draft: :project',
        'revert_to_draft_line' => ':actor reverted the project ":project" to draft.',
        'transition_subject' => 'Status change: :project',
        'transition_line' => ':actor changed the status of the project ":project".',
    ],

];
