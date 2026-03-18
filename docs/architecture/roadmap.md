# CICA-GPRO Professionalization & Open Source Roadmap

## Phase 1 — Fondations Professionnelles 🏗️ (TERMINEE ✅)
- [x] **Permissions & RBAC (Spatie)**
    - [x] Installer `spatie/laravel-permission`
    - [x] Migrer la colonne `role` vers le système RBAC
    - [x] Définir les rôles de base : `IT_ADMIN` (Global), `ORG_ADMIN`, `MANAGER`, `MEMBER`
    - [x] Créer les Policies pour `User` (et bypass IT_ADMIN)
- [x] **Modèle Multi-Tenant (Organisation)**
    - [x] Créer le modèle `Organization`
    - [x] Ajouter `organization_id` aux tables `User`, `Project`, etc.
    - [x] Implémenter le scoping global via `QueryServices` (Isolation des données)
- [x] **Gestion Système & Admin**
    - [x] Interface de gestion des Rôles (Multi-tenant)
    - [x] Interface de gestion des Permissions (Global)
    - [x] Interface de gestion des Organisations (CRUD sécurisé)
- [x] **Traçabilité (Audit Logs)**
    - [x] Mettre en place `spatie/laravel-activitylog`
    - [x] Interface de visualisation des logs d'audit

## Phase 2 — Authentification & Onboarding Multi-tenant 🔐
- [ ] **Flux d'Inscription Premium (SaaS Ready)**
    - [ ] Création du compte utilisateur (Sans organisation obligatoire au départ)
    - [ ] Onboarding : Choix entre "Créer une organisation" ou "Rejoindre via code"
    - [ ] Action CQRS `RegisterNewOrganizationAction` (Setup complet : Org + Admin)
    - [ ] Design Glassmorphism des pages Login/Register
- [ ] **Système d'Invitations (Membre)**
    - [ ] Interface d'invitation (Email + Rôle assigné à l'avance)
    - [ ] Lien d'invitation sécurisé avec signature URL
    - [ ] Intégration automatique dans l'organisation lors de l'inscription
- [ ] **Stabilité des Sessions**
    - [ ] Middleware automatique pour fixer le `PermissionsTeamId` (Spatie)
    - [ ] Redirection intelligente selon le rôle au Login

## Phase 3 — Modèle Économique & SaaS Readiness 💰
- [ ] **Gestion des Statuts d'Organisation**
    - [ ] Implémenter les statuts : `trial`, `active`, `suspended`, `expired`
    - [ ] Middleware `EnsureOrganizationIsActive` pour bloquer les écritures si impayé
- [ ] **Plans & Souscription**
    - [ ] Définition des quotas par rôle/organisation dans `OrganizationService`
    - [ ] Interface de gestion d'abonnement pour l'ORG_ADMIN

## Phase 4 — Notifications & Retour Utilisateur 🔔
- [ ] **Système de Toasts (Instant UI)**
    - [ ] Standardisation du trait `WithToastNotifications` sur 100% des composants
    - [ ] Gestion des animations de succès/erreur sur toutes les actions
- [ ] **Système de Notifications (Persistantes)**
    - [ ] Initialiser la table `notifications` (Laravel standard)
    - [ ] Implémenter la "Cloche de notification" dans la Navbar
    - [ ] Notifications Email pour les évènements critiques (Invitations, Rapports)

## Phase 5 — Architecture Clean & Qualité 🚀
- [ ] **Refactoring Projet & Activité (CQRS)**
    - [ ] Migrer `Projects` et `Activities` vers Action/QueryService
    - [ ] Améliorer les calculs de progression automatiques
- [ ] **Tests & Robustesse**
    - [ ] Écrire des tests Feature (Pest) pour les flux d'inscription et d'isolation des données
- [ ] **Documentation & OS Readiness**
    - [ ] Finaliser README.md et guide de contribution
