<?php

return [

    'title' => 'Workflow',
    'change_status' => 'Changer le statut',
    'history' => 'Historique des validations',
    'comment_placeholder' => 'Commentaire (optionnel)...',
    'confirm_transition' => 'Changer le statut en ":status" ?',
    'transition_success' => 'Statut changé en ":status".',

    'invalid_transition' => 'Transition impossible de ":from" vers ":to".',
    'only_creator_can_submit' => 'Seul le créateur du projet peut le soumettre.',
    'only_manager_can_review' => 'Seuls les gestionnaires et administrateurs peuvent réviser/approuver.',
    'only_admin_can_activate' => 'Seul un administrateur peut activer un projet.',
    'unauthorized' => 'Vous n\'êtes pas autorisé à effectuer cette action.',

    'actions' => [
        'submit' => 'a soumis le projet',
        'review' => 'a pris en charge la révision',
        'approve' => 'a approuvé le projet',
        'reject' => 'a rejeté le projet',
        'activate' => 'a activé le projet',
        'revert_to_draft' => 'a remis le projet en brouillon',
        'transition' => 'a changé le statut',
    ],

    'mail' => [
        'view_project' => 'Voir le projet',
        'comment' => 'Commentaire',
        'submit_subject' => 'Projet soumis : :project',
        'submit_line' => ':actor a soumis le projet ":project" pour validation.',
        'review_subject' => 'Projet en révision : :project',
        'review_line' => ':actor a pris en charge la révision du projet ":project".',
        'approve_subject' => 'Projet approuvé : :project',
        'approve_line' => ':actor a approuvé le projet ":project".',
        'reject_subject' => 'Projet rejeté : :project',
        'reject_line' => ':actor a rejeté le projet ":project".',
        'activate_subject' => 'Projet activé : :project',
        'activate_line' => ':actor a activé le projet ":project".',
        'revert_to_draft_subject' => 'Projet remis en brouillon : :project',
        'revert_to_draft_line' => ':actor a remis le projet ":project" en brouillon.',
        'transition_subject' => 'Changement de statut : :project',
        'transition_line' => ':actor a changé le statut du projet ":project".',
    ],

];
