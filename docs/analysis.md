# Rapport d'Analyse du Projet CICA-GPRO

Ce document détaille l'architecture, les fonctionnalités et les composants clés du repository `new-log-frame-laravel-10`.

## 1. Vue d'Ensemble
Le projet **CICA-GPRO** est une application web de gestion et de suivi de projets (Monitoring & Evaluation) conçue spécifiquement pour les ONG. Elle repose sur la méthodologie du **Cadre Logique**.

## 2. Pile Technologique (Tech Stack)
- **Framework Backend** : Laravel 10.x
- **Frontend** : Livewire 3.x (Composants réactifs sans JS complexe), Alpine.js, Tailwind CSS.
- **Moteur de Build** : Vite.
- **Authentification** : Laravel Breeze (intégré).
- **Génération de documents** : 
    - `phpword/phpword` : Exportation de projets au format Word (.docx).
    - `spatie/browsershot` : Exportation en PDF via Headless Chrome.
- **Tests** : Pest PHP.
- **Internationalisation** : Support du Français (fr) et de l'Anglais (en).

## 3. Architecture de la Base de Données
Le système est structuré autour du cycle de vie d'un projet :

### Configuration (Admin IT)
- `roles` & `departments` : Gestion des accès et de la structure organisationnelle.
- `project_types` & `dynamic_project_fields` : Permet de créer des formulaires personnalisés pour chaque type de projet (ex: Projet Humanitaire vs Projet de Développement).

### Gestion de Projet
- `projects` : Table centrale contenant les métadonnées du projet (nom, statut, dates, etc.).
- `project_contexts` & `project_documents` : Informations contextuelles et pièces jointes.
- `logical_frameworks` : L'épine dorsale du projet.
    - `specific_objectives` : Objectifs spécifiques liés au cadre.
    - `results` : Résultats attendus pour chaque objectif.
    - `activities` & `sub_activities` : Actions concrètes pour atteindre les résultats.

### Suivi et Evaluation (M&E)
- `resources` : Ressources allouées aux activités.
- `budgets` & `quarterly_budgets` : Planification financière.
- `progress_trackers` : Suivi de l'avancement (pourcentage, justifications).
- `qualitative_evaluations` : Évaluations qualitatives des actions.

## 4. Organisation du Code
- **Models (`app/Models/`)** : Reflètent fidèlement la structure de la base de données avec des relations Eloquent bien définies.
- **Controllers (`app/Http/Controllers/VBeta/`)** : Gèrent la logique de haut niveau, notamment les exports (Word/PDF) et les vues principales.
- **Composants Livewire (`app/Livewire/VBeta/`)** : Gèrent l'essentiel de l'interactivité (formulaires de création de projet, édition du cadre logique, gestion des membres).
- **Helpers (`app/Helpers/helpers.php`)** : Fonctions utilitaires pour le formuatage des dates et des textes (extraits, dates amicales).
- **Routes (`routes/web.php`)** : Organisées en groupes (Admin IT, Membre ONG) sous le préfixe `v_beta`.

## 5. Fonctionnalités Clés
1. **Formulaires Dynamiques** : L'administrateur définit des champs (texte, sélection, date) qui s'affichent lors de la création d'un projet selon son type.
2. **Cycle de Validation** : Workflow de Brouillon -> En attente -> Actif -> Terminé.
3. **Gestion du Cadre Logique** : Interface complexe pour saisir les objectifs, résultats et activités en une seule fois (souvent géré par des composants Livewire imbriqués).
4. **Exportation Multi-format** : Génération automatique de rapports Word et PDF basés sur les données du projet.

## 6. Points d'attention
- **Bug de duplication (`syncActivities`)** : Un problème a été identifié lors de l'édition des activités.
- **Architecture des champs dynamiques** : Utilisation actuelle de délimiteurs dans des champs texte, transition recommandée vers JSON.
