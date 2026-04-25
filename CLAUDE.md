# CICA-GPRO

## Stack
PHP 8.2+ / Laravel 10 / Livewire 3 / Alpine.js / Tailwind CSS / Vite / Pest PHP
ORM : Eloquent (UUID primary keys) | Auth : Sanctum + Spatie Permission | Multi-tenancy : trait custom Multitenantable | Audit : Spatie Activity Log
Exports : Browsershot (PDF) + PHPWord (DOCX) | Icons : Blade Lucide Icons

## Contexte
Systeme intelligent de gestion de projets (GPRO) pour le CICA. Multi-tenant par organisation, avec cadre logique hierarchique (Objectif General > Objectifs Specifiques > Resultats > Activites > Sous-activites). Gestion RBAC complete (system_admin, org_admin, org_user, independent).

## Fichiers de memoire du projet

A consulter selon la situation :

- **TASKS.md** -- etat d'avancement, a lire en debut de session et a mettre a jour en fin de tache.
- **DECISIONS.md** -- choix d'architecture faits. A lire AVANT de proposer un changement structurel. A completer quand une decision importante est prise.
- **PATTERNS.md** -- bouts de code recurrents du projet. A consulter avant d'ecrire du code similaire pour rester coherent.
- **ERRORS.md** -- bugs deja rencontres + solutions. A consulter AVANT de debugger un symptome.

## Regles de travail

- Lire `TASKS.md` avant de commencer.
- Mettre a jour `TASKS.md` apres chaque tache finie (case cochee + ligne d'historique).
- Avant un gros changement, utiliser `/plan` et attendre validation avant de coder.
- Pas de nouvelle dependance sans demander.
- Quand un bug est resolu, proposer de l'ajouter a `ERRORS.md`.
- Quand une decision d'archi est prise, proposer de l'ajouter a `DECISIONS.md`.

## Regles de code

### Laravel / PHP
- Eloquent pour toutes les requetes DB. Pas de raw SQL sauf cas exceptionnels justifies.
- UUID pour toutes les primary keys (utiliser le pattern boot() existant dans les models).
- Trait `Multitenantable` sur tout model lie a une organisation.
- Trait `LogsActivity` (Spatie) sur tout model metier important.
- SoftDeletes sur les entites principales (Project, User, Activity).
- Enums PHP pour tous les statuts (pas de strings en dur). Chaque enum a `label()`, `color()`, `icon()`.
- Logique metier dans des Services (ex: ProjectService), pas dans les controllers.
- Controllers minces : juste du routing vers Livewire ou retour de vues.

### Livewire / Frontend
- Composants Livewire suffixes `Livewire` (ex: ProjectListLivewire).
- Utiliser `WithPagination` pour les listes.
- Trait `WithToastNotifications` pour les notifications utilisateur.
- Alpine.js pour les interactions JS legeres. Pas de jQuery.
- Composants UI reutilisables dans `resources/views/components/ui/`.
- Tailwind CSS uniquement. Pas de CSS custom sauf necessite absolue.

### Conventions generales
- Code minimal et lisible. Pas de commentaires qui repetent le code.
- Noms de variables et fonctions explicites, anglais.
- Pas de `dd()`, `dump()` ou `Log::debug()` oublies dans le code final.
- Gestion d'erreur explicite (pas de `catch` vide).
- Routes organisees par domaine dans `routes/domains/` (system, admin, project, resource).
- Middleware `account_type` pour le controle d'acces par type de compte.

## Regles d'economie de tokens (important)

- Si le sujet change completement, suggerer `/clear` a l'utilisateur.
- Si la conversation devient longue sans changer de sujet, suggerer `/compact`.
- Ne pas relire des fichiers deja lus dans la meme session sauf s'ils ont ete modifies.
- Reponses concises par defaut. Details uniquement si demandes.
- Pour les taches specialisees (review, tests, archi), utiliser les sub-agents -- leur contexte est isole et n'encombre pas la conversation principale.

## Sub-agents disponibles

Claude Code peut invoquer automatiquement :
- **reviewer** -- pour toute relecture de code
- **testeur** -- pour ecrire/maintenir les tests
- **architecte** -- pour les decisions d'architecture

Tu peux aussi les appeler explicitement : "utilise l'agent architecte pour...".
