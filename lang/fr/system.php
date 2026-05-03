<?php

return [

    // Root dashboard
    'root_dashboard' => [
        'title' => 'Supervision Plateforme',
        'subtitle' => 'Vue d\'ensemble de toutes les organisations et utilisateurs',

        'organizations' => 'Organisations',
        'users' => 'Utilisateurs',
        'projects' => 'Projets',
        'pending_invitations' => 'Invitations en attente',

        'top_orgs' => [
            'organizations' => 'Organisations',
            'members' => 'Membres',
            'projects' => 'Projets',
        ],

        'recent_users' => [
            'title' => 'Utilisateurs récents',
            'user' => 'Utilisateur',
            'role' => 'Rôle',
            'organization' => 'Organisation',
            'date' => 'Date',
        ],

        'pending_invitations_table' => [
            'title' => 'Invitations en attente',
            'email' => 'Email',
            'organization' => 'Organisation',
            'invited_by' => 'Invité par',
            'date' => 'Date',
        ],

        'no_orgs' => 'Aucune organisation enregistrée',
        'no_users' => 'Aucun utilisateur récent',
    ],

    // Organizations
    'organizations' => [
        'title' => 'Gestion des Organisations',
        'subtitle' => 'Administrez les organisations de la plateforme',
    ],

    // Users
    'users' => [
        'title' => 'Gestion des Utilisateurs',
        'subtitle' => 'Administrez les utilisateurs de la plateforme',
    ],

    // Emails
    'emails' => [
        'title' => 'Gestion des Emails',
        'subtitle' => 'Consultez et gérez les emails envoyés par la plateforme',
    ],

    // Roles & Permissions
    'roles' => [
        'title' => 'Gestion des Rôles',
    ],

    'permissions' => [
        'title' => 'Gestion des Permissions',
    ],

    // Organization management
    'org_management' => [
        'search_placeholder' => 'Rechercher une organisation...',
        'section_title' => 'Unités & Organisations',
        'name_id' => 'Nom & Identifiant',
        'contact' => 'Contact',
        'stats' => 'Statistiques',
        'slug' => 'Slug',
        'no_email' => 'Aucun email',
        'no_phone' => 'Sans contact',
        'members_count' => ':count Membres',
        'confirm_delete_org' => 'ACTION CRITIQUE : Souhaitez-vous vraiment supprimer définitivement cette organisation ? Toutes les données liées seront perdues.',
        'no_org' => 'Aucune organisation',
        'no_org_desc' => 'Il n\'y a pas encore d\'organisations enregistrées dans le système.',
        'modal_edit_title' => 'Configuration de l\'Organisation',
        'modal_create_title' => 'Nouvelle Entité',
        'label_name' => 'Désignation',
        'placeholder_name' => 'Ex: Direction Générale',
        'label_slug' => 'Identifiant unique (Slug)',
        'placeholder_slug' => 'ex: direction-generale',
        'label_email' => 'Email professionnel',
        'label_phone' => 'Ligne téléphonique',
        'label_address' => 'Siège social / Adresse',
        'label_description' => 'Présentation',
        'placeholder_description' => 'Brève description de l\'organisation...',
        'saving' => 'Enregistrement...',
        'save_changes' => 'Enregistrer les modifications',
        'create_org' => 'Créer l\'organisation',
    ],

    // Role management
    'role_management' => [
        'search_placeholder' => 'Rechercher un rôle...',
        'all_roles' => 'Tous les rôles',
        'global_roles' => 'Rôles Système (Globaux)',
        'section_title' => 'Rôles & Accès',
        'role' => 'Rôle',
        'scope' => 'Domaine / Organisation',
        'permissions' => 'Permissions',
        'org_unknown' => 'Org Inconnue',
        'global_system' => 'Global Système',
        'perms_count' => ':count Perms',
        'confirm_delete_role' => 'Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irréversible et retirera ce rôle à tous les utilisateurs concernés.',
        'no_role' => 'Aucun rôle',
        'no_role_desc' => 'Aucun rôle ne correspond à vos filtres.',
        'modal_create_title' => 'Nouveau Rôle Système',
        'modal_edit_title' => 'Configuration du Rôle',
        'label_name' => 'Nom Identifiant du Rôle',
        'placeholder_name' => 'Ex: MANAGER_PROJET',
        'label_org' => 'Organisation de Rattachement',
        'option_global' => '-- Système Global (Cross-org) --',
        'label_permissions' => 'Attribution des Permissions',
        'saving' => 'Enregistrement...',
        'create_role' => 'Créer le rôle',
        'save_changes' => 'Enregistrer les modifications',
    ],

    // Permission management
    'perm_management' => [
        'search_placeholder' => 'Filtrer les permissions...',
        'section_title' => 'Clés de Droits d\'Accès',
        'permission' => 'Permission',
        'guard' => 'Guard',
        'protection' => 'Protection',
        'critical' => 'Critique Système',
        'free' => 'Libre',
        'confirm_delete_perm' => 'Attention : la suppression d\'une permission peut impacter les accès aux fonctionnalités. Confirmer la suppression ?',
        'no_perm' => 'Aucune permission',
        'no_perm_desc' => 'Aucune règle d\'accès personnalisée n\'est encore définie.',
        'modal_edit_title' => 'Éditer la Permission',
        'modal_create_title' => 'Nouvelle Règle d\'Accès',
        'label_name' => 'Nom Identifiant (slug)',
        'placeholder_name' => 'Ex: rapport.valider',
        'hint' => 'Utilisez des points (.) ou des tirets (-) pour structurer vos noms technique de permission.',
        'saving' => 'Sauvegarde...',
        'update' => 'Mettre à jour',
        'add_to_system' => 'Ajouter au système',
    ],

    // Root organization list
    'root_org_list' => [
        'title' => 'Organisations',
        'subtitle' => 'Gestion de toutes les organisations de la plateforme',
        'search_placeholder' => 'Rechercher une organisation...',
        'no_org' => 'Aucune organisation trouvée',
        'organization' => 'Organisation',
        'members' => 'Membres',
        'projects' => 'Projets',
        'enter' => 'Entrer',
        'suspend' => 'Suspendre',
        'activate' => 'Activer',
        'confirm_suspend' => 'Suspendre :name ?',
    ],

    // Root user list
    'root_user_list' => [
        'search_placeholder' => 'Rechercher par nom ou email...',
        'all_orgs' => 'Toutes les organisations',
        'all_roles' => 'Tous les roles',
        'section_title' => 'Utilisateurs globaux',
        'user' => 'Utilisateur',
        'organization' => 'Organisation',
        'no_org' => 'Aucune',
        'unknown' => 'Inconnu',
        'verified' => 'Vérifié',
        'blocked' => 'Bloqué',
        'confirm_block' => 'Bloquer cet utilisateur ?',
        'confirm_unblock' => 'Débloquer cet utilisateur ?',
        'confirm_reset' => 'Envoyer un lien de réinitialisation de mot de passe à :email ?',
        'no_user' => 'Aucun utilisateur',
        'no_user_desc' => 'Aucun utilisateur ne correspond aux critères de recherche.',
    ],

    // Root email suppression
    'email_suppression' => [
        'title' => 'Emails - Liste de suppression',
        'description' => 'Gestion des emails bloqués (bounce, désabonnement, plainte).',
        'bounced' => 'Bounced',
        'unsubscribed' => 'Désabonné',
        'complained' => 'Plainte',
        'search_placeholder' => 'Rechercher un email...',
        'all_reasons' => 'Toutes les raisons',
        'section_title' => 'Emails supprimés',
        'email' => 'Email',
        'reason' => 'Raison',
        'details' => 'Détails',
        'confirm_remove' => 'Retirer cet email de la liste ?',
        'remove' => 'Retirer',
        'no_email' => 'Aucun email supprimé',
        'no_email_desc' => 'La liste de suppression est vide.',
    ],

    // Audit / Activity history
    'audit' => [
        'title' => 'Historique des activités',
        'total_events' => 'Total: :count évènements',
        'system' => 'Système',
        'initial_data' => 'Données initiales enregistrées.',
        'no_activity' => 'Aucune activité enregistrée pour le moment.',
    ],

];
