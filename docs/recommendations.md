# Propositions d'Améliorations Architecturales - CICA-GPRO

## 1. Transition vers le format JSON (Hautement Recommandé)

Le système actuel utilise des délimiteurs (`delimiter_start` / `delimiter_end`) injectés dans des champs `longText`. Cette approche est risquée car elle dépend de la non-altération manuelle du texte par l'utilisateur.

### Solution Proposée
Utiliser une colonne `json` nommée `dynamic_attributes` ou `data` dans la table `projects`.

**Avantages :**
- **Robustesse** : Pas de risque de casser le parsing en cas de faute de frappe dans le texte.
- **Recherche** : Possibilité d'utiliser les fonctions JSON de SQL (MySQL/PostgreSQL) pour filtrer les projets par attribut.
- **Simplicité Laravel** : Accès direct via `$project->dynamic_attributes['field_name']`.

---

## 2. Optimisation du cycle de vie des Activités

La méthode `syncActivities` présente actuellement des risques de duplication et de mélange de données car elle se base sur l'indexation au lieu d'IDs uniques et stables.

### Recommandation
- Passer systématiquement par des UUIDs pour chaque activité, même en mode brouillon côté frontend.
- Utiliser la méthode `updateOrCreate` de Laravel pour garantir l'idempotence de la synchronisation.

---

## 3. Préparation de la Présentation Client

Pour la démonstration de ce soir :
- **Argument de l'Autonomie** : Montrez que l'ONG peut créer ses propres modèles de formulaires sans intervention technique.
- **Fiabilité des Données** : Expliquez que le passage au JSON garantit qu'aucune donnée métier ne sera perdue ou corrompue.
- **Visualisation de l'Avancement** : Mettez en avant les barres de progression automatiques basées sur les activités du cadre logique.
