# CICA-GPRO Professionalization & Open Source Roadmap

## Phase 1 — Fondations Professionnelles 🏗️
- [ ] **Permissions & RBAC (Spatie)**
    - [ ] Installer `spatie/laravel-permission`
    - [ ] Migrer la colonne `role` vers le système RBAC
    - [ ] Définir les rôles de base : `Super-Admin`, `Administrateur`, `Responsable Projet`, `Consultant`
    - [ ] Créer les Policies pour `Project` et `Activity`
- [x] **Modèle Multi-Tenant (Organisation)**
    - [x] Créer le modèle `Organization`
    - [x] Ajouter `organization_id` aux tables `User`, `Project`, `Activity`
    - [ ] Créer le Middleware d'isolation des données
- [ ] **Consolidation Base de Données**
    - [ ] Audit et suppression des colonnes inutiles
    - [ ] Fusionner `Activity` et `SubActivity`
    - [ ] Standardisation des noms de colonnes (`creator_id`, `responsible_id`)

## Phase 2 — Architecture Clean & CQRS 🏛️
- [ ] **Refactoring Logic Métier**
    - [ ] Créer les premières `Actions` pour la création/édition de Projet
    - [ ] Extraire les calculs de statistiques vers des `QueryServices`
    - [ ] Migrer les composants Livewire pour qu'ils n'appellent que des Actions
- [ ] **Standardisation UI (Composants)**
    - [ ] Audit 100% des vues pour l'uniformité `x-ui`
    - [ ] Améliorer les états de chargement (Skeletons)

## Phase 3 — Audit & AI Readiness 🤖
- [ ] **Traçabilité (Audit Logs)**
    - [ ] Mettre en place un système de log des changements sur les modèles sensibles
- [ ] **Documentation API**
    - [ ] Installer et configurer Swagger/OpenAPI pour la future IA

## Phase 4 — Qualité & Publication 🚀
- [ ] **Tests & Robustesse**
    - [ ] Mettre en place Pest et écrire des tests pour les Actions critiques
- [ ] **Documentation Open Source**
    - [ ] Finaliser README.md et CONTRIBUTING.md
