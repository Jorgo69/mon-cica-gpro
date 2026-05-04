# Guide de Contribution

Merci de contribuer a **CICA-GPRO** ! Ce guide vous aide a demarrer.

## Prerequis

- PHP 8.2+
- Composer 2
- Node.js 18+ / npm
- SQLite (dev) ou MySQL/PostgreSQL (prod)

## Installation locale

```bash
git clone https://github.com/cave-tech/cica-gpro.git
cd cica-gpro
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan gpro:install
npm run dev
php artisan serve
```

## Workflow Git

1. Fork le projet
2. Creez une branche depuis `development` :
   - `feature/nom-feature` pour une fonctionnalite
   - `fix/nom-bug` pour une correction
3. Codez, testez, commitez
4. Ouvrez une Pull Request vers `development`

## Conventions de code

### PHP / Laravel
- **PSR-12** pour le style
- **Eloquent** pour toutes les requetes DB (pas de raw SQL)
- **UUID** pour toutes les primary keys
- **Trait Multitenantable** sur tout model lie a une organisation
- **Trait LogsActivity** (Spatie) sur les models metier importants
- **Enums PHP** pour les statuts avec `label()`, `color()`, `icon()`
- **Services** pour la logique metier, controllers minces
- **SoftDeletes** sur les entites principales

### Frontend
- **Tailwind CSS** uniquement (pas de CSS custom sauf necessite)
- **Alpine.js** pour les interactions JS legeres
- **Composants `x-ui.*`** pour l'uniformite du design
- Pas de jQuery

### Nommage
- Classes PHP : `PascalCase`
- Methodes/proprietes : `camelCase`
- Vues Blade : `kebab-case`
- Tables DB : `snake_case` pluriel
- Composants Livewire : suffixe `Livewire` (ex: `ProjectListLivewire`)

## Tests

Toute nouvelle fonctionnalite ou correction **doit** inclure des tests.

```bash
# Tous les tests
php artisan test

# En parallele
php artisan test --parallel

# Un fichier specifique
php artisan test tests/Feature/MonTest.php
```

Framework : [Pest PHP](https://pestphp.com). Helpers dans `tests/Pest.php`.

## Structure du projet

```
app/
  Actions/       — Actions metier (RegisterUser, SaveMember...)
  Console/       — Commandes artisan
  Enums/         — Statuts, types (PHP enums)
  Events/        — Events broadcasting
  Http/
    Controllers/V1/  — Controllers minces
    Middleware/       — AccountType, SetOrgContext...
  Livewire/V1/   — Composants Livewire
  Models/        — Eloquent (UUID, Multitenantable)
  Notifications/ — 14 notifications (database + mail + FCM)
  Services/      — Logique metier
  Traits/        — Multitenantable, HasMeta...

plugins/         — Plugins (systeme d'extensions)
routes/domains/  — Routes par domaine (system, admin, project, resource)
```

## Plugins

GPRO supporte un systeme de plugins. Pour creer un plugin :

```
plugins/votre-vendor/votre-plugin/
  plugin.json          — Manifeste (nom, hooks, permissions)
  src/
    VotreServiceProvider.php
  resources/views/     — Vues Blade (optionnel)
```

Voir `plugins/gpro/usaid-report/` comme exemple.

## i18n

Toutes les chaines utilisateur passent par `__()`. Fichiers dans `lang/fr/` et `lang/en/`.

## Questions ?

Ouvrez une [issue](https://github.com/cave-tech/cica-gpro/issues) ou contactez l'equipe.
