<?php

return [

    'greeting' => 'Bonjour :name,',
    'greeting_simple' => 'Bonjour,',
    'salutation' => '— :app',
    'view_project' => 'Voir le projet',
    'view_dashboard' => 'Voir le tableau de bord',
    'unsubscribe' => 'Se desabonner',
    'someone' => "Quelqu'un",
    'an_admin' => 'Un administrateur',

    // ActivityAssignedNotification
    'activity_assigned' => [
        'subject' => 'Activite assignee : :activity',
        'greeting' => 'Bonjour :name,',
        'line1' => '**:assigner** vous a assigne une activite sur le projet **:project**.',
        'line2' => 'Activite : **:activity**',
        'action' => 'Voir le projet',
        'title' => 'Activite assignee',
        'message' => ':assigner vous a assigne l\'activite ":activity".',
    ],

    // ActivityProgressUpdatedNotification
    'activity_progress_updated' => [
        'subject' => 'Activite mise a jour — :progress%',
        'line1' => "L'activite **:activity** du projet **:project** est passee a **:progress%** de realisation.",
        'action' => 'Voir le projet',
        'title' => 'Progression Activite Mise a jour',
        'message' => "L'activite \":activity\" est passee a :progress% de realisation.",
    ],

    // ProjectStatusUpdatedNotification
    'project_status_updated' => [
        'subject' => 'Projet ":project" — Statut modifie',
        'line1' => 'Le statut du projet **:project** a ete modifie.',
        'line2' => '**:old_status** → **:new_status**',
        'action' => 'Voir le projet',
        'title' => 'Statut Projet Modifie',
        'message' => 'Le projet ":project" est passe de :old_status a :new_status.',
    ],

    // ProjectSubmittedNotification
    'project_submitted' => [
        'subject' => 'Nouveau projet soumis : :project',
        'line1' => '**:submitter** a soumis le projet **:project** pour validation.',
        'line2' => 'Code projet : :code',
        'action' => 'Voir le projet',
        'title' => 'Nouveau projet soumis',
        'message' => ':submitter a soumis le projet ":project" pour validation.',
    ],

    // InvitationNotification
    'powered_by' => 'Propulse par',
    'tagline' => 'Gestion intelligente de projets pour ONG',

    'invitation' => [
        'subject' => 'Invitation a rejoindre :organization',
        'line1' => '**:sender** vous invite a rejoindre l\'espace **:organization** sur :app.',
        'line1_rich' => 'vous invite a rejoindre l\'organisation :organization sur ' . config('app.name') . ', la plateforme de gestion de projets.',
        'line2' => "Cliquez sur le bouton ci-dessous pour accepter l'invitation :",
        'action' => "Accepter l'invitation",
        'line3' => "Vous pouvez aussi utiliser ce code d'invitation : **:code**",
        'line4' => 'Ce code est valable 7 jours.',
        'your_role' => 'Votre role',
        'or_use_code' => 'Ou utilisez ce code d\'invitation :',
    ],

    // CommentPostedNotification
    'comment_posted' => [
        'subject_mention' => ':author vous a mentionne dans un commentaire',
        'subject_comment' => ':author a commente une activite',
        'action' => 'Voir',
        'title_mention' => 'Vous avez ete mentionne',
        'title_comment' => 'Nouveau commentaire',
        'message_mention' => ':author vous a mentionne : ":excerpt"',
        'message_comment' => ':author a commente : ":excerpt"',
    ],

    // ActivityOverdueNotification
    'activity_overdue' => [
        'subject_escalation' => '[ESCALADE] Activite en retard depuis :days jours',
        'subject_normal' => 'Activite en retard : :activity',
        'line_escalation1' => "**ESCALADE** — L'activite **:activity** du projet **:project** est en retard depuis **:days jours**.",
        'line_escalation2' => "Le responsable (:responsible) n'a pas mis a jour cette activite. Votre intervention est requise.",
        'line_normal1' => "L'activite **:activity** du projet **:project** est en retard de **:days jour(s)**.",
        'line_normal2' => 'Date limite depassee : **:date**',
        'action' => 'Voir le projet',
        'title_escalation' => '[ESCALADE] Activite en retard (:daysj)',
        'title_normal' => 'Activite en retard (:daysj)',
        'message_escalation' => "[ESCALADE] L'activite \":activity\" est en retard de :days jour(s).",
        'message_normal' => "L'activite \":activity\" est en retard de :days jour(s).",
    ],

    // BudgetThresholdNotification
    'budget_threshold' => [
        'level_exceeded' => 'depasse',
        'level_reached' => 'atteint :percent%',
        'subject' => 'Budget :level — :project',
        'line1' => 'Le budget du projet **:project** a **:level**.',
        'action' => 'Voir le projet',
        'title' => 'Budget :level',
        'message' => 'Le budget du projet ":project" a :level.',
    ],

    // DeadlineApproachingNotification
    'deadline_approaching' => [
        'label_tomorrow' => 'demain',
        'label_days' => 'dans :days jours',
        'subject' => 'Echeance :label : :activity',
        'line1' => "L'activite **:activity** du projet **:project** arrive a echeance **:label**.",
        'line2' => 'Date limite : **:date**',
        'action' => 'Voir le projet',
        'title' => 'Echeance :label',
        'message' => "L'activite \":activity\" arrive a echeance :label.",
    ],

    // WeeklyDigestNotification
    'weekly_digest' => [
        'subject' => 'Resume hebdomadaire — :app',
        'line_intro' => 'Voici le resume de la semaine :',
        'overdue' => '**:count** activite(s) en retard',
        'upcoming' => '**:count** echeance(s) cette semaine',
        'completed' => '**:count** activite(s) terminees cette semaine',
        'projects' => '**:count** projet(s) actifs',
        'action' => 'Voir le tableau de bord',
    ],
];
