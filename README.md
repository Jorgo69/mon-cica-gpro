# CICA-GPRO v2.0 — Systeme Intelligent de Gestion de Projets

[![Version](https://img.shields.io/badge/Version-2.0.0-blue.svg)]()
[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![Livewire 3](https://img.shields.io/badge/Livewire-3.x-blue.svg)](https://livewire.laravel.com)
[![Pest](https://img.shields.io/badge/Tests-462%20passed-brightgreen.svg)](https://pestphp.com)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

**CICA-GPRO** est une plateforme open source de gestion de projets basee sur le **Cadre Logique (LogFrame)**. Concue pour les ONG, associations et organisations de developpement, elle combine une architecture multi-tenant robuste avec une experience utilisateur moderne.

Disponible en deux modes :
- **Self-hosted** (open source) — installation libre, tout illimite
- **SaaS** — heberge avec plans et gestion centralisee

---

## Fonctionnalites

### Gestion de projets
- Structure hierarchique : Objectif General > Objectifs Specifiques > Resultats > Activites > Sous-activites
- Workflow d'approbation configurable (brouillon, soumis, en revision, approuve, actif...)
- Templates de projet duplicables
- Champs dynamiques par type de projet
- Indicateurs avec suivi de progression et tendances

### Budgets et finances
- Budget planifie vs depenses reelles avec alertes de depassement
- Multi-devise (8 devises : XOF, EUR, USD, GBP, XAF, NGN, CHF, CAD)
- Taux de change manuels par organisation
- Burn rate et projections

### Collaboration
- Commentaires par activite (fil de discussion, mentions @user)
- Pieces jointes (upload multi-fichiers)
- Notifications intelligentes (in-app, email, push FCM)
- Rappels automatiques (echeances J-7/J-3/J-1, retards, digest hebdo)
- Calendrier 6 vues (annee, semestre, trimestre, mois, semaine, jour) + export iCal

### Exports et rapports
- PDF (DomPDF / Chromium)
- Word (PHPWord)
- Excel (activites, budget, indicateurs, export complet multi-feuilles)
- Tableau de bord bailleur public (lien partage avec token)
- Diagramme Gantt/Timeline

### IA integree
- 9 providers supportes (Groq, Gemini, OpenAI, Anthropic, Mistral, DeepSeek, Cohere, Together, custom)
- Assistance par champ (generation de contenu, resume executif, analyse dashboard)
- Configuration multi-niveau : global > organisation > membre

### Administration
- Multi-tenant par organisation avec isolation stricte
- RBAC 4 niveaux (ROOT, org_admin, manager, member) + permissions granulaires
- Invitations par email avec auto-accept
- Social Auth (Google, Facebook, Microsoft)
- Audit logs complet (Spatie Activity Log)
- RGPD : export donnees, anonymisation, suppression planifiee
- API REST v1 (15 endpoints, auth Sanctum, rate limiting)
- Webhooks HMAC-SHA256 (10 evenements)
- Carte geographique des projets (Leaflet)
- PWA (installable, mode hors-ligne)

---

## Stack technique

| Couche | Technologies |
|--------|-------------|
| Backend | PHP 8.2+, Laravel 12, Eloquent (UUID) |
| Frontend | Livewire 3, Alpine.js, Tailwind CSS, Vite |
| Auth | Sanctum, Spatie Permission, Socialite |
| Exports | PDF Studio (DomPDF), PHPWord, Maatwebsite Excel |
| Notifications | Database, Mail, Firebase FCM |
| Tests | Pest PHP (462 tests, 997 assertions) |
| CI/CD | GitHub Actions |

---

## Installation

### Prerequis

- PHP 8.2+
- Composer 2
- Node.js 18+ / npm
- SQLite (dev) ou MySQL/PostgreSQL (prod)

### Installation rapide

```bash
git clone https://github.com/cave-tech/cica-gpro.git
cd cica-gpro

composer install
npm install

cp .env.example .env
php artisan key:generate
```

### Installation guidee (recommandee)

La commande interactive configure tout automatiquement :

```bash
php artisan gpro:install
```

Elle effectue :
- Migration de la base de donnees
- Seed des donnees de base (roles, permissions, categories, plans)
- Creation de l'organisation et du compte administrateur
- Lien storage et optimisation du cache

### Installation manuelle

```bash
php artisan migrate --seed
php artisan storage:link
php artisan optimize

npm run build      # Production
npm run dev        # Developpement
php artisan serve
```

### Configuration

Editez `.env` selon vos besoins :

```env
# Mode : saas ou selfhosted
GPRO_MODE=selfhosted

# Base de donnees (SQLite pour dev rapide)
DB_CONNECTION=sqlite

# Email (Mailpit en dev, SMTP Gmail en prod)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com

# IA (optionnel — Groq gratuit, sans restriction geo)
GROQ_API_KEY=

# Social Auth (optionnel — laisser vide pour desactiver)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
```

### Fichiers .env par base de donnees

Le projet fournit des fichiers preconfigures — copiez celui qui correspond a votre situation :

| Fichier | Utilisation |
|---------|-------------|
| `.env.example` | Template generique (SQLite par defaut) |
| `.env.example.sqlite` | Dev local, petites instances |
| `.env.example.mysql` | Production avec MySQL |
| `.env.example.postgres` | Production avec PostgreSQL |

```bash
# Exemple : installer avec MySQL
cp .env.example.mysql .env
php artisan key:generate
# Editez .env avec vos identifiants DB, puis :
php artisan migrate --seed
```

> Le code est **100% DB-agnostic** — zero SQL brut. SQLite, MySQL et PostgreSQL fonctionnent sans aucune modification.

---

## Deploiement

### Docker (recommande pour selfhost)

```bash
# MySQL + phpMyAdmin
make up

# PostgreSQL + pgAdmin
make up-pg
```

Guide complet : [docs/docker-guide.md](docs/docker-guide.md)

### VPS / Serveur dedie (sans Docker)

Guide pas a pas (Nginx, SSL, Supervisor, cron) : [docs/installation-manual.md](docs/installation-manual.md)

### Hebergement mutualise (LWS, o2switch...)

Voir la section dediee dans [docs/installation-manual.md](docs/installation-manual.md#hebergement-mutualise-lws-o2switch-etc)

### Deploiement CI/CD (GitHub Actions → LWS FTP)

Guide : [docs/deployment.md](docs/deployment.md)

---

## Tests

```bash
# Tous les tests
php artisan test

# Tests en parallele
php artisan test --parallel

# Un fichier specifique
php artisan test tests/Feature/Api/ApiV1Test.php
```

---

## Comptes par defaut (seeder)

| Role | Email | Mot de passe |
|------|-------|-------------|
| ROOT (SaaS uniquement) | root@cica-gpro.com | password |
| Org Admin | admin@projexia.org | password |
| Member | member@projexia.org | password |

En mode **selfhosted**, le premier utilisateur inscrit devient automatiquement administrateur.

---

## Structure du projet

```
app/
  Actions/         # Actions metier (RegisterUser, SaveMember, etc.)
  Console/         # Commandes artisan (rappels, rapports, purge)
  Enums/           # Statuts, types, devises (PHP enums)
  Http/
    Controllers/   # Controllers minces (routing vers Livewire)
    Middleware/     # AccountType, SetOrgContext, CheckAiAccess
  Livewire/        # Composants Livewire (V1/)
  Models/          # Eloquent models (UUID, Multitenantable)
  Notifications/   # 10 notifications (database + mail + FCM)
  Services/        # Logique metier (Workflow, Budget, RGPD, IA)
  Traits/          # Multitenantable, HasMeta, HasNotificationPreferences

resources/views/
  components/ui/   # Composants reutilisables (button, card, modal, etc.)
  livewire/        # Vues Livewire
  layouts/         # Layouts app, guest, shared

routes/
  domains/         # Routes par domaine (system, admin, project, resource)
  api.php          # API REST v1
  auth.php         # Authentification
```

---

## API REST v1

Authentification par Bearer token (Sanctum). Gestion des tokens dans Settings.

```bash
# Exemple : lister ses projets
curl -H "Authorization: Bearer YOUR_TOKEN" \
     https://your-domain.com/api/v1/projects
```

Endpoints disponibles : `/me`, `/projects`, `/activities`, `/members`, `/stats`, `/notifications`, `/audit-logs`.

---

## Contribution

1. Fork le projet
2. Cree ta branche (`git checkout -b feature/ma-feature`)
3. Commit tes changements (`git commit -m 'feat: description'`)
4. Push vers ta branche (`git push origin feature/ma-feature`)
5. Ouvre une Pull Request

### Conventions

- **PHP** : PSR-12, Eloquent uniquement (pas de raw SQL), UUID pour les PK
- **Frontend** : Tailwind CSS uniquement, Alpine.js pour le JS leger
- **Enums** : PHP enums avec `label()`, `color()`, `icon()` pour tous les statuts
- **Services** : logique metier dans `app/Services/`, controllers minces
- **Tests** : Pest PHP, `RefreshDatabase`, helpers dans `tests/Pest.php`
- **i18n** : toutes les chaines via `__()`, fichiers FR + EN

---

## Licence

Ce projet est distribue sous licence [MIT](LICENSE).

Developpe par **Cave-Tech**.
