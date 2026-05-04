<?php

return [

    'title' => 'Paramètres',
    'subtitle' => 'Personnalisez votre expérience selon vos préférences',

    // Tabs
    'appearance' => 'Apparence',
    'language' => 'Langue',
    'notifications' => 'Notifications',
    'linked_accounts' => 'Comptes liés',
    'organization' => 'Organisation',

    // Organization Profile
    'org_profile' => 'Profil de l\'organisation',
    'org_logo' => 'Logo',
    'upload_logo' => 'Changer le logo',
    'remove_logo' => 'Supprimer le logo',
    'org_name' => 'Nom de l\'organisation',
    'org_description' => 'Description',
    'org_description_placeholder' => 'Decrivez brievement votre organisation (visible dans les emails et partages)...',
    'org_website' => 'Site web',
    'org_contact_email' => 'Email de contact',
    'org_contact_phone' => 'Telephone',
    'org_branding_hint' => 'Ces informations apparaitront dans les emails d\'invitation et les tableaux de bord partages avec vos partenaires.',

    // Ownership transfer
    'transfer' => [
        'title' => 'Propriete de l\'organisation',
        'you_are_owner' => 'Vous etes le proprietaire de cette organisation.',
        'desc' => 'Le proprietaire a le controle total : il ne peut pas etre supprime ou retrograde. Vous pouvez transferer ce role a un autre administrateur.',
        'transfer_btn' => 'Transferer la propriete',
        'no_other_admin' => 'Ajoutez d\'abord un autre administrateur pour pouvoir transferer la propriete.',
        'modal_title' => 'Transferer la propriete',
        'warning' => 'Attention : apres le transfert, vous resterez administrateur mais ne serez plus proprietaire. Cette action est reversible uniquement par le nouveau proprietaire.',
        'select_admin' => 'Nouvel admin proprietaire',
        'choose' => 'Choisir un administrateur',
        'confirm' => 'Transferer',
        'success' => 'Propriete transferee a :name.',
        'not_owner' => 'Vous n\'etes pas le proprietaire de cette organisation.',
        'select_target' => 'Selectionnez un administrateur.',
        'target_not_admin' => 'Le destinataire doit etre un administrateur de votre organisation.',
    ],

    // Theme
    'theme' => [
        'title' => 'Thème de l\'interface',
        'desc' => 'Basculez entre le mode clair et sombre',
        'light' => 'Clair',
        'dark' => 'Sombre',
    ],

    // Density
    'density' => [
        'title' => 'Densité d\'affichage',
        'compact' => 'Compact',
        'comfortable' => 'Confortable',
        'spacious' => 'Spacieux',
        'compact_desc' => 'Affichage condensé pour plus de contenu visible',
        'comfortable_desc' => 'Espacement équilibré pour un confort de lecture',
        'spacious_desc' => 'Espacement généreux pour une lecture détendue',
    ],

    // Language
    'language_settings' => [
        'title' => 'Langue et région',
        'french' => 'Français',
        'english' => 'English',
    ],

    // Date format
    'date_format' => [
        'title' => 'Format de date',
        'french' => 'JJ/MM/AAAA',
        'american' => 'MM/DD/YYYY',
        'iso' => 'AAAA-MM-JJ',
    ],

    // Email notifications
    'email_notifications' => [
        'title' => 'Notifications par email',
        'desc' => 'Recevez des notifications par email pour les événements importants',
    ],

    // Timezone
    'timezone' => [
        'title' => 'Fuseau horaire',
        'desc' => 'Définissez votre fuseau horaire pour l\'affichage des dates et heures',
    ],

    // Profile
    'profile' => [
        'title' => 'Mon Profil',
        'subtitle' => 'Gérez vos informations personnelles',

        'avatar' => 'Photo de profil',
        'change_avatar' => 'Changer la photo',
        'remove_avatar' => 'Supprimer la photo',
        'choose_avatar' => 'Choisir une photo',

        'personal_info' => 'Informations personnelles',
        'full_name' => 'Nom complet',
        'email' => 'Adresse email',
        'phone' => 'Téléphone',
        'sex' => 'Sexe',
        'country' => 'Pays',
        'city' => 'Ville',
        'city_placeholder' => 'Tapez le nom de votre ville...',

        'saved' => 'Profil mis à jour',

        'password_title' => 'Modifier le mot de passe',
        'current_password' => 'Mot de passe actuel',
        'new_password' => 'Nouveau mot de passe',
        'confirm_password' => 'Confirmer le mot de passe',

        'danger_zone' => 'Zone de danger',
        'delete_account' => 'Supprimer mon compte',
        'delete_account_desc' => 'La suppression de votre compte est irréversible. Toutes vos données seront définitivement effacées.',
        'delete_account_confirm' => 'Êtes-vous sûr de vouloir supprimer votre compte ?',
    ],

    // GDPR / Danger zone
    'delete' => [
        'danger_title' => 'Zone de danger',
        'export_title' => 'Exporter mes données',
        'export_desc' => 'Téléchargez une copie de toutes vos données personnelles au format JSON.',
        'export_btn' => 'Télécharger mes données',
        'export_org_title' => 'Exporter les données de l\'organisation',
        'export_org_desc' => 'Téléchargez une copie complète des données de votre organisation (membres, projets, budgets).',
        'export_org_btn' => 'Télécharger les données org',
        'delete_account_title' => 'Supprimer mon compte',
        'delete_account_desc' => 'Votre compte sera anonymisé et vos données personnelles supprimées. Cette action est irréversible.',
        'delete_account_btn' => 'Supprimer mon compte',
        'delete_org_title' => 'Supprimer l\'organisation',
        'delete_org_desc' => 'L\'organisation sera supprimée après un délai de 30 jours. Tous les membres seront détachés et les projets archivés. Vous pouvez annuler pendant cette période.',
        'delete_org_btn' => 'Planifier la suppression',
        'delete_org_cancel' => 'Annuler la suppression',
        'confirm_password' => 'Confirmez votre mot de passe pour continuer',
        'wrong_password' => 'Mot de passe incorrect.',
        'must_transfer_ownership' => 'Vous devez d\'abord transférer la propriété de l\'organisation à un autre administrateur avant de supprimer votre compte.',
        'not_owner' => 'Seul le propriétaire de l\'organisation peut effectuer cette action.',
        'deleted_user' => 'Utilisateur supprimé',
        'org_scheduled' => 'Suppression planifiée. L\'organisation sera supprimée dans 30 jours. Un email a été envoyé à tous les membres.',
        'org_cancelled' => 'Suppression annulée. L\'organisation ne sera pas supprimée.',
        'org_scheduled_at' => 'Suppression prévue le :date',
        'org_scheduled_warning' => 'Cette organisation est programmée pour être supprimée. Vous pouvez annuler cette action.',
    ],

];
