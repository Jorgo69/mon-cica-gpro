<?php

return [

    'title' => 'Liste des Projets',
    'subtitle' => 'Gérez et suivez l\'avancement de vos initiatives stratégiques',

    'new_project' => 'Nouveau Projet',
    'search_placeholder' => 'Titre, code, mots-clés...',

    'all_statuses' => 'Tous les statuts',
    'all_responsibles' => 'Tous les responsables',

    // Table headers
    'project' => 'Projet',
    'code' => 'Code',
    'status' => 'Statut',
    'responsible' => 'Responsable',
    'period' => 'Période',
    'actions' => 'Actions',

    'from' => 'Du',
    'to' => 'Au',

    'no_projects' => 'Aucun projet trouvé',
    'no_projects_desc' => 'Aucun projet ne correspond à vos critères de recherche.',

    // Show
    'show' => [
        'title' => 'Détails du Projet',
        'edit' => 'Modifier',
        'premium_report' => 'Rapport Premium',
        'back' => 'Retour',

        'tabs' => [
            'overview' => 'Vue d\'ensemble',
            'logframe' => 'Cadre Logique',
            'documents' => 'Documents',
            'analyses' => 'Analyses',
            'tracking' => 'Suivi',
            'history' => 'Historique',
        ],

        'general_info' => 'Informations générales',
        'short_title' => 'Titre abrégé',
        'period' => 'Période',
        'description' => 'Description',

        'context' => 'Contexte',
        'context_desc' => 'Contexte du projet',
        'justification' => 'Justification',
        'strategy' => 'Stratégie',
        'problem_analysis' => 'Analyse du problème',

        'format' => 'Format d\'affichage',
        'format_table' => 'Tableau',
        'format_tree' => 'Arborescence',
        'format_cards' => 'Cartes',

        'documents' => 'Documents',
        'no_documents' => 'Aucun document',
        'no_documents_desc' => 'Aucun document n\'a été ajouté à ce projet.',

        'analyses_coming' => 'Analyses à venir',
        'analyses_coming_desc' => 'Le module d\'analyses sera disponible prochainement.',

        'audit_trail' => 'Journal d\'audit',

        'not_found' => 'Projet introuvable',
        'not_found_desc' => 'Le projet demandé n\'existe pas ou a été supprimé.',
    ],

    // Project dashboard
    'dashboard' => [
        'title' => 'Tableau de bord : :name',
        'subtitle' => 'Suivi détaillé de l\'avancement du projet',

        'total' => 'Total',
        'completed' => 'Terminées',
        'in_progress' => 'En cours',
        'not_started' => 'Non démarrées',
        'overdue' => 'En retard',

        'search_placeholder' => 'Rechercher une activité...',
        'all_responsibles' => 'Tous les responsables',
        'all_statuses' => 'Tous les statuts',
        'per_page' => 'par page',

        'activity_details' => 'Détails de l\'activité',

        'description' => 'Description',
        'responsible' => 'Responsable',
        'status' => 'Statut',
        'progress' => 'Progression',
        'actions' => 'Actions',

        'no_activities' => 'Aucune activité',
        'no_activities_desc' => 'Ce projet n\'a pas encore d\'activités planifiées.',
    ],

    // Design (create/edit)
    'design' => [
        'create_title' => 'Créer un Projet',
        'edit_title' => 'Modifier le Projet',

        'key_info' => 'Informations clés',
        'key_info_desc' => 'Renseignez les informations de base du projet',

        'ai_tip' => 'Astuce IA',
        'ai_tip_desc' => 'L\'assistant IA peut vous aider à structurer votre cadre logique.',

        'project_title' => 'Titre du projet',
        'project_code' => 'Code du projet',
        'short_title' => 'Titre abrégé',
        'general_description' => 'Description générale',

        'start_date' => 'Date de début',
        'end_date' => 'Date de fin',
        'project_status' => 'Statut du projet',

        'steps' => 'Étapes',
        'progress' => 'Progression',
    ],

    // Proposal wizard
    'proposal' => [
        'step1_title' => 'Informations clés',
        'step1_desc' => 'Détails de base du projet',
        'step2_title' => 'Contexte & Documents',
        'step2_desc' => 'Description et fichiers pertinents',
        'step3_title' => 'Cadre Logique',
        'step3_desc' => 'But et objectifs spécifiques',
        'step4_title' => 'Résultats Attendus',
        'step4_desc' => 'Livrables concrets du projet',
        'step5_title' => 'Activités',
        'step5_desc' => 'Actions préliminaires',
        'step6_title' => 'Finalisation',
        'step6_desc' => 'Vérification et soumission',

        'project_type' => 'Type de projet',
        'select_type' => 'Sélectionner un type de projet',
        'type_desc' => 'Description du type de projet',

        'project_title' => 'Titre du projet',
        'project_code' => 'Code du projet',
        'short_title' => 'Titre abrégé (optionnel)',

        'start_date' => 'Date de début',
        'end_date' => 'Date de fin',

        'no_dynamic_fields' => 'Aucun champ dynamique configuré pour ce type de projet',
    ],

    // Templates
    'templates' => [
        'title' => 'Bibliothèque de Templates',
        'subtitle' => 'Démarrez rapidement avec un projet pré-configuré',
        'use' => 'Utiliser ce template',
        'no_templates' => 'Aucun template disponible',
        'no_templates_desc' => 'Il n\'y a pas encore de templates disponibles. Un administrateur peut marquer un projet existant comme template.',
        'activities_count' => ':count activités',
        'objectives_count' => ':count objectifs',
        'system_template' => 'Template système',
        'org_template' => 'Template organisation',
        'marked' => 'Projet marqué comme template',
        'unmarked' => 'Projet retiré des templates',
        'mark_as_template' => 'Marquer comme template',
        'unmark_template' => 'Retirer des templates',
        'duplicated' => 'Projet créé avec succès depuis le template',
        'duplicate_project' => 'Dupliquer le projet',
    ],

];
