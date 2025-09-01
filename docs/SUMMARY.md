# 📚 Documentation du Projet

> *Dernière mise à jour : {{ date('d/m/Y') }}*  
> *Système de suivi et de gestion des activités et projets*

---

## 📂 Table des matières

- [Système de Progression](progression-system.md)
  - Calcul de la progression des activités
  - Détection automatique du retard
  - Agrégation au niveau du projet

- [Modèles de Données](data-model.md)
  - Structure de `Project`, `Activity`, `SubActivity`
  - Relations entre entités
  - Champs obligatoires et optionnels

- [Policy](activity-policy.md)
  - Structure
    - [Project Policy](../app/Policies/ProjectPolicy.php)
    - [Activity Policy](../app/Policies/ActivityPolicy.php)
    - [SubActivity Policy](../app/Policies/SubActivityPolicy.php)

- [Gestion des Statuts](status-management.md)
  - Liste des statuts possibles
  - Correspondance métier / technique
  - Évolution future (ex: ajout de nouveaux états)

- [Formulaire de Catégorie](category-form.md)
  - Utilisation du modal
  - Validation et sauvegarde
  - Gestion des erreurs

- [Bonnes Pratiques Livewire](livewire-best-practices.md)
  - Mise à jour en temps réel
  - Gestion des événements
  - Performance et optimisation

- [API & Intégrations](api-integration.md)
  - Points d’accès API
  - Authentification
  - Exemples de requêtes

---

## 🛠️ À venir

<!-- Tu peux ajouter ici des fichiers que tu créeras plus tard -->
- [ ] Dashboard metrics
- [ ] User permissions system
- [ ] Export & reporting
- [ ] Notifications system

---

> ℹ️ Ce document est vivant.  
> N’hésite pas à **ajouter, modifier, organiser** selon l’évolution du projet.