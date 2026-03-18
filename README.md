# 🚀 CICA-GPRO : Système Intelligent de Gestion de Projets

[![Laravel 10](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![Livewire 3](https://img.shields.io/badge/Livewire-3.x-blue.svg)](https://livewire.laravel.com)
[![Pest](https://img.shields.io/badge/Pest-v2-green.svg)](https://pestphp.com)
[![License](https://img.shields.io/badge/License-Proprietary-black.svg)]()

**CICA-GPRO** est une plateforme moderne et puissante de gestion de projets basée sur le **Cadre Logique (LogFrame)**. Conçue pour les organisations exigeantes, elle combine une architecture multi-tenant robuste avec une expérience utilisateur premium.

---

## ✨ Fonctionnalités Clés

### 🏗️ Architecture & Sécurité
- **Multi-Tenancy Natif** : Isolation totale des données par organisation via `organization_id`.
- **RBAC Complet** : Gestion fine des permissions avec `spatie/laravel-permission` (IT_ADMIN, ORG_ADMIN, MANAGER, MEMBER).
- **Audit Logs** : Historique complet des modifications sur chaque entité (Projets, Activités, Ressources).

### 📊 Pilotage de Projet (Cadre Logique)
- **Structure Hiérarchique** : Projets > Cadre Logique > Objectifs Spécifiques > Résultats > Activités.
- **Suivi de Progression** : Calcul automatique de l'avancement pondéré et historique des jalons.
- **Tableaux de Bord Dynamiques** : Analytics en temps réel via Chart.js pour visualiser les budgets et les taux d'exécution.

### 💎 Expérience Utilisateur (Premium UX)
- **Recherche Globale (Ctrl+K)** : Moteur de recherche ultra-rapide couvrant les projets, membres et activités.
- **Reporting PDF Premium** : Génération de rapports haute fidélité (Vecteurs/Browsershot) avec templates Modernes et Classiques.
- **Centre de Notifications** : Alertes en temps réel sur l'avancement, les retards et les validations requises.
- **UI Component Library** : Bibliothèque de composants `x-ui` cohérente, accessible et supportant le mode sombre.

---

## 🛠️ Stack Technique

- **Backend** : PHP 8.2+, Laravel 10.x
- **Frontend** : Livewire 3, Alpine.js, Tailwind CSS
- **Base de données** : PostgreSQL / MySQL / SQLite
- **Exports** : Spatie Browsershot (Puppeteer), Laravel Excel
- **Tests** : Pest PHP

---

## 🚀 Installation Rapide

```bash
# 1. Cloner le projet
git clone [repository-url]
cd new-log-frame-laravel-10

# 2. Installer les dépendances
composer install
npm install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Migrer et Seeder (Multi-Tenant inclus)
php artisan migrate --seed

# 5. Lancer le serveur de dev
php artisan serve
npm run dev
```

---

## 🧪 Tests Automatisés

Nous utilisons **Pest** pour garantir la robustesse des actions métiers.

```bash
php artisan test
```

---

## 🤝 Contribution

Veuillez consulter le fichier [CONTRIBUTING.md](CONTRIBUTING.md) pour plus de détails sur nos standards de développement et le workflow Git.

---

## 📄 Licence

Ce logiciel est propriétaire. Tous droits réservés à **Cave-Tech**.
