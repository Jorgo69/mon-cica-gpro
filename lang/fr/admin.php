<?php

return [

    // Members
    'members' => [
        'title' => 'Gestion des Membres',
        'subtitle' => 'Gérez les membres de votre organisation',
        'add' => 'Ajouter un membre',
        'search_placeholder' => 'Rechercher un membre...',

        'name' => 'Nom',
        'email' => 'Email',
        'phone' => 'Téléphone',
        'role' => 'Rôle',
        'department' => 'Département',
        'country' => 'Pays',
        'city' => 'Ville',
        'actions' => 'Actions',

        'no_members' => 'Aucun membre',
        'no_members_desc' => 'Aucun membre trouvé pour cette organisation.',

        'modal_add' => 'Ajouter un membre',
        'modal_edit' => 'Modifier le membre',
        'modal_details' => 'Détails du membre',
        'modal_confirm_delete' => 'Confirmer la suppression',

        'confirm_delete_text' => 'Voulez-vous vraiment supprimer :name ? Cette action est irréversible.',
        'delete_permanently' => 'Supprimer définitivement',
        'delete_confirm_title' => 'Supprimer le membre ?',
        'section' => 'Membres',
        'not_specified' => 'Non renseigné',
    ],

    // Categories
    'categories' => [
        'title' => 'Gestion des Catégories',
        'subtitle' => 'Organisez vos projets et activités par catégorie',
        'new' => 'Nouvelle catégorie',

        'search_placeholder' => 'Rechercher une catégorie...',
        'all_types' => 'Tous les types',

        'name' => 'Nom',
        'type' => 'Type',
        'description' => 'Description',
        'source' => 'Source',
        'actions' => 'Actions',

        'no_categories' => 'Aucune catégorie',
        'no_categories_desc' => 'Aucune catégorie trouvée.',

        'modal_edit' => 'Modifier la catégorie',
        'modal_new' => 'Nouvelle catégorie',

        'system' => 'Système',
        'organization' => 'Organisation',
        'protected' => 'Protégée',

        'confirm_delete' => 'Supprimer cette catégorie ?',

        'type_label' => 'Type de catégorie',
        'select_type' => 'Sélectionner un type',
        'name_label' => 'Nom de la catégorie',
        'name_placeholder' => 'Ex: Personnel, Infrastructure...',
        'desc_placeholder' => 'Décrivez cette catégorie...',
        'update' => 'Mettre à jour',
        'create' => 'Créer la catégorie',
        'section' => 'Catégories',
        'no_categories_detail' => 'Créez votre première catégorie ou ajustez vos filtres.',
    ],

    // Invitations
    'invitations' => [
        'title' => 'Gestion des Invitations',
        'subtitle' => 'Invitez de nouveaux membres à rejoindre votre organisation',
        'invite' => 'Inviter',

        'search_placeholder' => 'Rechercher une invitation...',
        'all_statuses' => 'Tous les statuts',

        'email' => 'Email',
        'organization' => 'Organisation',
        'role' => 'Rôle',
        'status' => 'Statut',
        'invited_by' => 'Invité par',
        'expires_at' => 'Expire le',
        'code' => 'Code',
        'actions' => 'Actions',

        'resend' => 'Renvoyer',
        'revoke' => 'Révoquer',

        'no_invitations' => 'Aucune invitation',
        'no_invitations_desc' => 'Aucune invitation en cours.',

        'send' => 'Envoyer l\'invitation',
        'invite_member' => 'Inviter un membre',
        'search_by_email' => 'Rechercher par email...',
        'no_invitations_detail' => 'Invitez des membres à rejoindre votre organisation.',
        'confirm_resend' => 'Renvoyer une nouvelle invitation ?',
        'confirm_revoke' => 'Révoquer cette invitation ?',
        'cooldown' => 'Veuillez patienter :minutes minute(s) avant de renvoyer.',
        'cooldown_hint' => 'Cooldown anti-spam actif',
        'target_organization' => 'Organisation cible',
        'select_organization' => 'Sélectionner une organisation',
        'account_type' => 'Type de compte',
        'email_placeholder' => 'collaborateur@exemple.com',
        'roles' => [
            'member' => 'Membre',
            'manager' => 'Gestionnaire',
            'admin' => 'Administrateur',
            'org_admin' => 'Administrateur',
            'supervisor' => 'Superviseur',
        ],
        'role_desc' => [
            'member' => 'Peut consulter les projets et suivre ses activites.',
            'manager' => 'Peut creer et gerer des projets, assigner des activites.',
            'admin' => 'Acces complet : gestion des membres, projets, parametres de l\'organisation.',
        ],
    ],

    // Project types
    'types' => [
        'title' => 'Types de Projet',
        'subtitle' => 'Configurez les types de projet et leurs champs personnalisés',
        'new' => 'Nouveau type',

        'name' => 'Nom',
        'description' => 'Description',
        'fields' => 'Champs',
        'projects' => 'Projets',
        'actions' => 'Actions',
    ],

    // Trash
    'trash' => [
        'title' => 'Corbeille',
        'subtitle' => 'Gérez les éléments supprimés — restaurez ou supprimez définitivement',

        'members' => 'Membres',
        'members_section' => 'Membres supprimés',
        'project_types' => 'Types de projet',
        'project_types_section' => 'Types de projet supprimés',
        'projects' => 'Projets',
        'projects_section' => 'Projets supprimés',

        'name' => 'Nom',
        'email' => 'Email',
        'role' => 'Rôle',
        'deleted_at' => 'Supprimé le',
        'actions' => 'Actions',
        'category' => 'Catégorie',
        'title_col' => 'Titre',
        'code' => 'Code',
        'status' => 'Statut',

        'no_members' => 'Aucun membre supprimé',
        'no_types' => 'Aucun type supprimé',
        'no_projects' => 'Aucun projet supprimé',

        'view_details' => 'Détails de l\'élément',
        'permanent_delete' => 'Suppression définitive',
        'irreversible' => 'Cette action est irréversible.',
        'confirm_permanent_delete' => 'Voulez-vous supprimer définitivement cet élément ?',
    ],

    // Projects (admin list)
    'projects' => [
        'title' => 'Tous les Projets',
        'subtitle' => 'Vue d\'administration de tous les projets du système',
        'section' => 'Projets',

        'search_placeholder' => 'Rechercher un projet...',
        'all_statuses' => 'Tous les statuts',
        'all_responsibles' => 'Tous les responsables',

        'title_col' => 'Titre',
        'code' => 'Code',
        'status' => 'Statut',
        'responsible' => 'Responsable',
        'start_date' => 'Début',
        'end_date' => 'Fin',
        'actions' => 'Actions',

        'no_projects' => 'Aucun projet trouvé',
        'no_projects_desc' => 'Modifiez vos filtres ou attendez que des projets soient créés.',
    ],

    // Project types (form)
    'types' => [
        'title' => 'Types de Projet',
        'subtitle' => 'Configurez les types de projet et leurs champs personnalisés',
        'new' => 'Nouveau type',

        'list_title' => 'Types de Projets',
        'list_subtitle' => 'Configurez les catégories et champs dynamiques de vos projets',
        'create_new' => 'Créer un nouveau type',
        'no_types' => 'Aucun type de projet',
        'no_types_desc' => 'Créez votre premier type de projet pour commencer.',
        'confirm_delete' => 'Êtes-vous sûr de vouloir supprimer ce type de projet ?',

        'edit_title' => 'Modifier le type de projet',
        'new_title' => 'Nouveau type de projet',
        'form_subtitle' => 'Configurez les informations et champs dynamiques',

        'key_info' => 'Informations Clés',
        'name' => 'Nom',
        'name_label' => 'Nom du type de projet',
        'name_placeholder' => 'Ex: Projet de Développement',
        'description' => 'Description',
        'category' => 'Catégorie',
        'no_category' => 'Aucune catégorie',
        'fields' => 'Champs',
        'projects' => 'Projets',
        'actions' => 'Actions',

        'dynamic_fields' => 'Champs Dynamiques',
        'question_label' => 'Libellé de la question',
        'field_type' => 'Type de champ',
        'field_name' => 'Nom du champ',
        'render_type' => 'Type de rendu',
        'order' => 'Ordre',
        'target_field' => 'Champ cible',
        'target_placeholder' => 'Ex: title',
        'section' => 'Section',
        'section_placeholder' => 'Ex: Informations de base',
        'required' => 'Obligatoire',
        'add_field' => 'Ajouter un champ',

        'type_text' => 'Texte (simple)',
        'type_textarea' => 'Zone de texte (long)',
        'type_select' => 'Liste déroulante',
        'type_date' => 'Date',
        'type_number' => 'Nombre',
        'render_select' => 'Liste déroulante',
        'render_radio' => 'Boutons radio',
        'render_checkbox' => 'Cases à cocher',

        'options_title' => 'Options de la liste',
        'option_label' => 'Libellé',
        'option_value' => 'Valeur',
        'add_option' => 'Ajouter une option',

        'save' => 'Sauvegarder',
    ],

];
