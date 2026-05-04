# Contrôleurs V-Beta (Modern Interface)

Ce dossier regroupe les contrôleurs de la version Beta (V-Beta), qui utilise une architecture moderne basée sur Livewire et une séparation stricte des domaines.

## Architecture & Responsabilités
Conformément à la [Charte de Développement](file:///docs/architecture/coding_standards.md), ces contrôleurs sont "maigres" :
*   **Validation** : Ils valident la structure de base de la requête.
*   **Orchestration** : Ils appellent des `Actions` ou des `Services`.
*   **Réponse** : Ils retournent la vue Blade ou Redirect avec les notifications adéquates.

## Contrôleurs & Interactions

### [DashboardController.php](file:///app/Http/Controllers/V1/DashboardController.php)
*   **Rôle** : Portail central après connexion.
*   **Logique** : 
    *   Récupère les statistiques agrégées (Projets actifs, Budgets).
    *   Distingue l'affichage selon le rôle de l'utilisateur (Admin vs Manager).
*   **Données reçues** : Aucune (Lecture seule / GET).
*   **Données envoyées** : Collection de statistiques (`$stats`), liste de projets récents.

### [ProjectController.php](file:///app/Http/Controllers/V1/ProjectController.php) (Si existant)
*   **Rôle** : Gestion du cycle de vie des projets.
*   **Actions appelées** : `CreateProjectAction`, `UpdateProjectStatusAction`.

## Section : API & Interactions IA
Chaque méthode publique dans ces contrôleurs est une porte d'entrée potentielle pour l'automatisation. Nous privilégions le passage de paramètres via des DTO (Data Transfer Objects) pour garantir la compatibilité avec les agents IA.

---
*Note : Si une logique métier dépasse 10 lignes, elle doit être extraite dans un Service.*
