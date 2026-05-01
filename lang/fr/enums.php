<?php

return [

    // ActivityStatus
    'activity_status' => [
        'draft' => 'Brouillon',
        'abandoned' => 'Abandonné',
        'stopped' => 'En Arrêté',
        'pending' => 'En Attente',
        'ongoing' => 'En Cours',
        'suspended' => 'Suspendu',
        'completed' => 'Terminé',
        'overdue' => 'En retard',
    ],

    // ProjectStatus
    'project_status' => [
        'draft' => 'Brouillon',
        'pending' => 'En attente',
        'active' => 'En cours',
        'on_hold' => 'En pause',
        'completed' => 'Terminé',
        'cancelled' => 'Annulé',
    ],

    // AccountType
    'account_type' => [
        'root' => 'Super Administrateur',
        'org_admin' => 'Administrateur Espace',
        'org_user' => 'Collaborateur',
        'independent' => 'Indépendant',
    ],

    // InvitationStatus
    'invitation_status' => [
        'pending' => 'En attente',
        'accepted' => 'Acceptée',
        'expired' => 'Expirée',
        'revoked' => 'Révoquée',
    ],

    // AdminCategoryType
    'admin_category_type' => [
        'project_status' => 'Statut de projet',
        'activity_status' => 'Statut d\'activité',
        'project_category' => 'Catégorie de projet',
        'budget_category' => 'Catégorie budgétaire',
        'resource_type' => 'Type de ressource',
        'document_type' => 'Type de document',
    ],

    // NotificationType
    'notification_type' => [
        'project_submitted' => 'Projet soumis',
        'project_status_updated' => 'Statut projet modifié',
        'activity_assigned' => 'Activité assignée',
        'activity_progress_updated' => 'Progression activité',
        'deadline_approaching' => 'Échéance proche',
        'activity_overdue' => 'Activité en retard',
        'budget_threshold' => 'Seuil budget',
        'weekly_digest' => 'Résumé hebdomadaire',
        'invitation' => 'Invitation',
    ],

    // LogframeDisplayFormat
    'logframe_format' => [
        'table' => 'Tableau',
        'tree' => 'Arborescence',
        'cards' => 'Fiches',
    ],

    // OrganizationStatus
    'organization_status' => [
        'trial' => 'Période d\'essai',
        'active' => 'Active',
        'suspended' => 'Suspendue',
        'inactive' => 'Inactive',
    ],

    // Currency
    'currency' => [
        'XOF' => 'Franc CFA (BCEAO)',
        'XAF' => 'Franc CFA (BEAC)',
        'EUR' => 'Euro',
        'USD' => 'Dollar US',
        'GBP' => 'Livre Sterling',
        'CHF' => 'Franc Suisse',
        'CAD' => 'Dollar Canadien',
        'NGN' => 'Naira',
    ],
];
