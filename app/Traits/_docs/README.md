# Traits Réutilisables (Shared State & Behavior)

Ce dossier contient des Traits PHP utilisés pour partager de la logique entre plusieurs modèles de manière uniforme.

## Traits Disponibles

### [Multitenantable.php](file:///app/Traits/Multitenantable.php)
*   **Responsabilité** : Isolation des données par organisation.
*   **Fonctionnement** :
    1.  **Scope Global** : Ajoute automatiquement un `where('organization_id', ...)` sur toutes les requêtes (sauf pour les Admins IT).
    2.  **Auto-Assignation** : Remplit automatiquement le champ `organization_id` lors de la création d'un nouvel enregistrement (via l'utilisateur connecté).
*   **Utilisation** : Ajouter `use Multitenantable;` dans n'importe quel modèle qui doit être isolé par entreprise (ex: `Project`, `User`, `Activity`).

---
*Note : Si un modèle utilise ce trait, il DOIT posséder une colonne `organization_id` dans sa table associée.*
