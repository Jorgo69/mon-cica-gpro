<?php

return [

    // Dashboard
    'dashboard_overview' => 'Ce tableau de bord affiche les statistiques globales de vos projets. Utilisez les filtres temporels pour affiner la periode.',

    // Projects
    'project_code' => 'Le code projet est un identifiant unique genere automatiquement. Vous pouvez le personnaliser.',
    'project_type' => 'Le type de projet determine les champs dynamiques disponibles et la structure du cadre logique.',
    'project_status' => '<strong>Brouillon</strong> : en preparation. <strong>En cours</strong> : actif. <strong>Termine</strong> : cloture.',
    'logframe' => 'Le cadre logique est une hierarchie : Objectif General &gt; Objectifs Specifiques &gt; Resultats &gt; Activites. Chaque niveau peut avoir des indicateurs.',
    'logframe_indicators' => 'Les indicateurs mesurent l\'avancement. Definissez une valeur de base (baseline), une cible, et enregistrez les mesures au fil du temps.',

    // Activities
    'activity_progress' => 'La progression est un pourcentage de 0 a 100%. Elle est mise a jour manuellement ou via les sous-activites.',
    'activity_responsible' => 'Le responsable est la personne en charge de cette activite. Il recevra les rappels et notifications.',
    'sub_activities' => 'Les sous-activites decomposent une activite en taches plus petites. Leur progression moyenne calcule celle de l\'activite parente.',

    // Budget
    'budget_planned' => 'Le budget planifie correspond aux previsions initiales par ligne budgetaire.',
    'budget_real' => 'Les depenses reelles sont saisies au fur et a mesure. L\'ecart avec le planifie est calcule automatiquement.',
    'budget_burn_rate' => 'Le taux de consommation (burn rate) projette les depenses jusqu\'a la fin du projet base sur la vitesse actuelle.',

    // Templates
    'templates' => 'Les templates sont des projets pre-configures reutilisables. Marquez un projet existant comme template pour qu\'il apparaisse ici.',

    // Share
    'share_link' => 'Les liens de partage permettent a des personnes externes (bailleurs, partenaires) de consulter le projet sans avoir de compte. Definissez une date d\'expiration pour la securite.',

    // Timeline
    'timeline_gantt' => 'Le diagramme de Gantt affiche les activites sur une echelle temporelle. Les barres sont colorees selon le statut. La ligne rouge indique la date d\'aujourd\'hui.',

    // Indicators
    'indicator_tracking' => 'Suivez vos indicateurs en ajoutant des mesures regulieres. La tendance (fleche) montre l\'evolution recente.',
    'indicator_baseline' => 'La valeur de base est la situation initiale avant le debut du projet.',
    'indicator_target' => 'La cible est la valeur a atteindre a la fin du projet.',

    // Settings
    'settings_theme' => 'Le theme modifie l\'apparence visuelle. Le mode sombre reduit la fatigue oculaire.',
    'settings_notifications' => 'Configurez quelles notifications vous souhaitez recevoir par email, push ou dans l\'application.',

    // Admin
    'admin_categories' => 'Les categories organisent vos donnees par type (statut projet, categorie budget, type ressource, etc.).',
    'admin_invitations' => 'Invitez des collaborateurs par email. Ils recevront un lien pour rejoindre votre organisation.',
    'admin_roles' => 'Les roles definissent les permissions. ORG_ADMIN gere l\'organisation, MANAGER gere les projets, MEMBER participe.',

    // FAQ link
    'need_more_help' => 'Besoin d\'aide ? Consultez la <a href="/faq" class="text-accent underline">FAQ</a>.',

];
