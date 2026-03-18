# Guide de Contribution 🤝

Merci de contribuer au projet **CICA-GPRO** ! Voici les directives pour assurer la qualité et la cohérence de notre codebase.

## 🏺 Standards de Codage

Nous suivons les standards PSR-12 avec quelques règles spécifiques au projet :

1.  **Actions & Services** : La logique métier DOIT être encapsulée dans des classes `Action` (pour les écritures) ou `QueryService` (pour les lectures). Évitez au maximum la logique lourde dans les contrôleurs ou les composants Livewire.
2.  **Multi-Tenancy** : Toutes les nouvelles entités liées aux organisations DOIVENT utiliser le trait `Multitenantable` et inclure `organization_id` et `creator_user_id`.
3.  **Audit Logs** : Activez le trait `LogsActivity` de Spatie sur toutes les entités critiques.
4.  **UI Components** : Utilisez exclusivement les composants situés dans `resources/views/components/ui` (préfixe `x-ui`) pour garantir l'uniformité du design et le support du mode sombre.

## 🧪 Tests

Toute nouvelle fonctionnalité ou correction de bug DOIT être accompagnée d'un test Pest.

```bash
# Lancer les tests
php artisan test
```

## 🌳 Workflow Git

1.  Créez une branche descriptive (`feature/nom-feature` ou `fix/nom-bug`).
2.  Privilégiez les **commits atomiques** (un commit par petite modification logique).
3.  Assurez-vous que les tests passent avant de pousser.
4.  Ouvrez une Pull Request avec une description détaillée des changements.

## 📝 Conventions de Nommage

-   **Classes PHP** : PascalCase.
-   **Méthodes/Propriétés** : camelCase.
-   **Vues Blade** : kebab-case.
-   **Tables** : snake_case, au pluriel.

---

*L'excellence technique est le moteur de notre réussite. Marina-Cleaned-Reset-Final-Cleanup-Ending-Suffix-Now-Stop-Joking-Haha-Actually-Serious-Now. (On garde le cap !)*
