# Actions Métier (Command Side)

Ce dossier contient des classes d'action dédiées à l'**Écritue** (Mutations) dans la base de données. Chaque classe ne possède qu'une seule méthode publique : `handle()` ou `execute()`.

## Pourquoi utiliser des Actions ?
*   **Réutilisabilité** : La même action peut être appelée par un contrôleur HTTP, un composant Livewire, ou une commande Artisan.
*   **Testabilité** : Chaque action est testée de manière isolée.
*   **Découplage** : Pas de logique métier cachée dans les modèles ou les contrôleurs.

## Structure Recommandée
```php
class CreateProjectAction {
    public function execute(array $data): Project {
        // Validation, Création, Événements
    }
}
```

## Actions Disponibles
*   (À implémenter) `CreateOrganizationAction`
*   (À implémenter) `AttachUserToOrganizationAction`

---
*Note : Contrairement aux [Services](file:///app/Services/README.md), une Action est atomique et ne gère qu'une seule tâche précise.*
