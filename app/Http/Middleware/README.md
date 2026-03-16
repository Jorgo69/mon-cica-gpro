# Middlewares (Filtres de Requêtes)

Ce dossier contient les classes de Middleware qui interceptent les requêtes HTTP pour gérer la sécurité, l'authentification et le contexte global.

## Middlewares Spécifiques CICA-GPRO

### [SetOrganizationContext.php](file:///app/Http/Middleware/SetOrganizationContext.php)
*   **Responsabilité** : Sécurité et Isolation SaaS.
*   **Fonctionnement** :
    1.  Vérifie le statut de l'organisation de l'utilisateur connecté.
    2.  Bloque l'accès si l'organisation est `SUSPENDED` ou `INACTIVE`.
    3.  Autorise les Admins IT à bypasser les restrictions.
*   **Interactions** : Travaille de concert avec le trait `Multitenantable` pour garantir que l'environnement de l'utilisateur est sain.

---
*Note : Pour activer un middleware sur une route, utilisez le nom défini dans `app/Http/Kernel.php`.*
