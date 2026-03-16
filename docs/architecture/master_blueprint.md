# Master Blueprint : Road to Professionalization & Open Source (CICA-GPRO)

Ceci est le guide suprême de l'évolution du projet. Chaque étape doit être validée par l'utilisateur avant exécution.

---

## Phase 1 : Consolidation du Cœur (Priorité Actuelle)

### 1.1 Nettoyage de la Base de Données
*   **Multi-Tenancy (Organisation)** : Création de la table `organizations` pour isoler les données par entreprise.
*   **Fusion Activités** : Fusionner `Activity` et `SubActivity` en une table récursive.
*   **Audit des Tables** : Vérifier que chaque colonne est utile et typée correctement.

### 1.2 Permissions Professionnelles
*   **Installation Spatie Permissions** : Remplacer la colonne `role` simple par une gestion RBAC complète.
*   **Définition des Permissions** : Lister chaque action atomique (ex: `projects.view-any`, `projects.create`, `activities.validate-budget`).
*   **Mise en place des Policies** : Lier les modèles aux droits d'accès.

---

## Phase 2 : Refonte Architecturale (Clean Architecture)

### 2.1 Services & Actions
*   Extraire la logique métier des contrôleurs vers des `Actions` unitaires.
*   Créer des `QueryServices` pour les statistiques complexes du Dashboard.

### 2.2 Centralisation des Calculs
*   Un seul endroit pour calculer le pourcentage de progression d'un projet (`ProgressService`).

---

## Phase 3 : Expérience Utilisateur & Design

### 3.1 Standardisation UI
*   Audit de toutes les vues pour s'assurer que 100% des éléments utilisent les composants `x-ui`.
*   Amélioration des retours utilisateurs (Toasts, Skeletons de chargement).

### 3.2 Internationalisation (i18n) Pro
*   Organisation des fichiers JSON par module (`projects.json`, `admin.json`).

---

## Phase 4 : Préparation à l'IA (AI Readiness)

### 4.1 Audit Logs & Historique
*   Enregistrer chaque changement important (qui a changé le statut de l'activité ? pourquoi ?). C'est la donnée d'entraînement futur de l'IA.

### 4.2 API Interne
*   Création de points d'entrée simples et documentés (Swagger/OpenAPI) pour permettre à des agents IA d'interagir avec le moteur de CICA-GPRO.

---

## Phase 5 : Stratégie SaaS & Open Source

### 5.1 Business Model
*   **Hosted SaaS** : Toi (l'auteur) vends la facilité (hébergement, backup, support) sur `cica-pro.campuschine.org`.
*   **Open Core / Community** : Le code est public pour la transparence et la contribution, mais la version "Cloud" a des services ajoutés.

### 5.2 Protection & Licences
*   Le choix d'une licence comme **AGPLv3** obligera ceux qui modifient ton code pour le revendre en ligne à partager leurs modifications.
