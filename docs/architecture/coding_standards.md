# Charte de Développement & Standards Clean Code (CICA-GPRO)

Ce document définit les règles strictes de développement pour garantir que le projet reste "Open Source ready", maintenable sur le long terme et compatible avec une future intégration IA.

---

## 1. Principes Architecturaux (The Pillars)

### A. Séparation des Responsabilités (SoC)
*   **Controllers/Livewire** : Ils ne doivent gérer que la requête et la réponse. Aucune logique métier, aucun calcul, aucun update direct de base de données complexe.
*   **Services** : Utilisés pour la **Lecture** complexe ou les calculs partagés (ex: `StatisticsService`).
*   **Actions** : Utilisées pour l'**Écriture**. Une classe = Une action métier (ex: `CreateProjectAction`).
*   **Models** : Ils sont "maigres". Ils définissent les relations, les scopes et les casts. Pas de logique métier lourde.

### B. CQRS (Command Query Responsibility Segregation)
*   **Commands (Écriture)** : Utiliser des `Actions`. Elles valident les données et effectuent les mutations.
*   **Queries (Lecture)** : Utiliser des `QueryObjects` ou des `Services` pour extraire des données formatées pour l'UI ou l'IA.

### C. Permissions & Sécurité
*   Ne jamais vérifier les rôles en dur (`$user->role === 'admin'`).
*   Toujours utiliser des **Policies** et des **Permissions** (Spatie Laravel Permission).
*   Chaque action doit passer par `$user->can('action', $model)`.

---

## 2. Règles de Code (Strict Rules)

### A. Typage & Déclaration
*   **Type Hinting obligatoire** : Toutes les fonctions doivent avoir des types pour les arguments et le retour.
*   **Strict Types** : Utiliser `declare(strict_types=1);` dans les nouvelles classes de logique.
*   **Enums** : Obligatoires pour tous les états (Status, Priority, Roles).

### B. Naming Conventions
*   **Classes** : PascalCase descriptive (ex: `GenerateProjectReportAction`).
*   **Variables/Membres** : camelCase.
*   **Base de données** : snake_case, pluriel pour les tables.

### C. Documentation Locale (Folder-Level README)
*   Chaque dossier majeur (`Controllers`, `Models`, `Services`) doit contenir un `README.md`.
*   Ce fichier doit expliquer : Responsabilités, Entrées/Sorties, Interactions avec d'autres modules.
*   Chaque fonction complexe doit être documentée avec des commentaires expliquant le "Pourquoi".

### D. Stratégie de Migration & Seed
*   Favoriser des migrations propres et consolidées (éviter d'avoir 50 petites migrations pour une même table).
*   Le `DatabaseSeeder` doit être la source de vérité pour tester l'application en une commande (`php artisan migrate:fresh --seed`).

### C. Zéro "Magic Numbers" ou "Magic Strings"
*   Tout ce qui est réutilisable doit être mis en `const` ou dans un `Config` ou `Enum`.

---

## 3. Workflow de Modification

1.  **Planification** : Avant de coder, l'agent ou le développeur doit valider l'impact sur le Blueprint.
2.  **Migration** : Si la DB change, la migration doit être réversible et documentée.
3.  **Tests** : Chaque nouvelle Action doit avoir son test associé.

---

## 4. Design & UI
*   Utiliser exclusivement les composants `x-ui.*`.
*   Toute nouvelle page doit hériter de `x-app-layout`.
*   Le Dark Mode doit être géré via des classes sémantiques Tailwind.
