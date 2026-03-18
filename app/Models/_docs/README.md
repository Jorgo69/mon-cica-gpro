# Modèles de Données (Domain Models)

Ce dossier contient la définition de la structure de données et des relations métier de l'application CICA-GPRO.

## Principes Généraux
*   **UUID** : Tous les modèles utilisent des UUID comme clés primaires pour faciliter la consolidation de données et la sécurité.
*   **Casts & Enums** : Les états (status, role, type) sont systématiquement castés vers des Enums (`App\Enums`).
*   **Soft Deletes** : Utilisés pour garantir qu'aucune donnée métier n'est supprimée définitivement (Audit Trail).

## Modèles Principaux & Interactions

### [User.php](file:///app/Models/User.php)
*   **Rôle** : Identité de l'utilisateur.
*   **Interactions** :
    *   Appartient à une `Organization` (nullable pour les indépendants).
    *   Crée des `Project`.
    *   Est responsable d'une `Activity`.
    *   Soumet des `QualitativeEvaluation`.

### [Organization.php](file:///app/Models/Organization.php)
*   **Rôle** : Unité d'isolation (Multi-Tenancy).
*   **Interactions** : 
    *   Contient plusieurs `User`.
    *   Possède plusieurs `Project`.
    *   Gère son propre statut (Trial, Active, Suspended).

### [Project.php](file:///app/Models/Project.php)
*   **Rôle** : Entité centrale de gestion.
*   **Structure** : Lié à un `LogicalFramework` qui définit la pyramide des objectifs.
*   **Calculs** : Gère le calcul global de la progression via les activités rattachées.

### [Activity.php](file:///app/Models/Activity.php)
*   **Rôle** : Action atomique au sein d'un projet.
*   **Récursivité** : (Prévu) Fusion avec `SubActivity` pour permettre une hiérarchie infinie via `parent_id`.

---
*Note : Pour toute modification de schéma, référez-vous au [Rapport d'Audit DB](file:///docs/architecture/db_audit_report.md).*
