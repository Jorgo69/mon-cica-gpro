# Enums & Types Stricts

Ce dossier centralise toutes les énumérations PHP (PHP 8.1+) utilisées pour typer les colonnes de la base de données et la logique métier.

## Avantages
*   **Sécurité** : Impossible d'insérer une valeur non prévue.
*   **Auto-complétion** : Facilitée dans l'IDE.
*   **Lisibilité** : `ProjectStatus::ACTIVE` est plus clair que `'active'`.

## Enums Disponibles

### [AccountType.php](file:///app/Enums/AccountType.php)
Définit les rôles métier au sein d'une organisation : `ADMIN`, `SUPERVISOR`, `MANAGER`, `MEMBER`.

### [OrganizationStatus.php](file:///app/Enums/OrganizationStatus.php)
Gère le cycle de vie d'une entreprise dans le SaaS : `TRIAL`, `ACTIVE`, `SUSPENDED`, `INACTIVE`.

### [ActivityStatus.php](file:///app/Enums/ActivityStatus.php)
Statuts des activités du Cadre Logique (Draft, Ongoing, Completed, etc.).

---
*Note : Chaque Enum possède des méthodes `label()` et `color()` pour uniformiser l'affichage dans les vues Blade/Livewire.*
