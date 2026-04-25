# Journal des decisions

Chaque decision d'architecture importante prise sur ce projet, avec le raisonnement.
Format : date, decision, raison, alternatives ecartees.

---

## Modele d'entree (a copier)

### [AAAA-MM-JJ] Titre court de la decision
**Decision :** ce qui a ete choisi.
**Raison :** pourquoi.
**Alternatives ecartees :** ce qui a ete considere puis rejete, et pourquoi.
**Impact :** ce que ca change pour la suite.

---

## Decisions

### [2026-04-25] UUID comme cle primaire sur tous les models
**Decision :** Utiliser des UUID (v4 via Str::uuid()) au lieu d'auto-increment pour toutes les tables.
**Raison :** Securite (pas d'enumeration d'IDs), compatibilite multi-tenant, preparation a une eventuelle architecture distribuee.
**Alternatives ecartees :** Auto-increment classique (plus simple mais expose les IDs sequentiels), ULID (pas encore natif dans le projet).
**Impact :** Tous les models doivent declarer `$incrementing = false`, `$keyType = 'string'`, et generer l'UUID dans `boot()`. Les foreign keys sont en `uuid()` dans les migrations.

### [2026-04-25] Multi-tenancy par trait Multitenantable (isolation organization_id)
**Decision :** Isolation des donnees par `organization_id` via un trait custom `Multitenantable` qui applique un Global Scope automatique.
**Raison :** Solution legere, sans package externe, qui couvre le besoin (chaque organisation voit uniquement ses donnees). Le system_admin bypass le scope pour voir tout.
**Alternatives ecartees :** Package stancl/tenancy (trop lourd pour le besoin, base par tenant = overhead), middleware seul (risque d'oubli par model).
**Impact :** Tout nouveau model lie a une organisation doit utiliser le trait. Le middleware `SetOrganizationContext` configure le contexte en debut de requete.

### [2026-04-25] Livewire 3 comme couche interactive principale (pas de SPA)
**Decision :** Utiliser Livewire 3 + Alpine.js pour toute l'interactivite, pas de framework SPA (Vue/React).
**Raison :** Coherence avec l'ecosysteme Laravel, pas besoin d'API frontend separee, courbe d'apprentissage reduite, SSR natif.
**Alternatives ecartees :** Vue.js + Inertia (plus reactif mais double stack a maintenir), React (completement decorrele de Laravel).
**Impact :** Les composants complexes sont des classes Livewire (suffixe `Livewire`). Alpine.js gere les micro-interactions JS. Pas de build JS complexe.

### [2026-04-25] Spatie Permission pour le RBAC avec team_id = organization_id
**Decision :** Utiliser `spatie/laravel-permission` configure en mode teams, ou `team_id` correspond a `organization_id`.
**Raison :** Package mature, bien integre a Laravel, supporte nativement l'isolation par equipe/tenant.
**Alternatives ecartees :** Bouncer (moins maintenu), systeme custom (cout de dev et de maintenance eleve).
**Impact :** Les roles et permissions sont scopes par organisation. `setPermissionsTeamId()` est appele dans le middleware `SetOrganizationContext`.

### [2026-04-25] Routes organisees par domaine metier (routes/domains/)
**Decision :** Decouper les routes web en fichiers par domaine : `system.php`, `admin.php`, `project.php`, `resource.php`.
**Raison :** Clarte et maintenabilite. Chaque domaine a ses middleware et prefixes isoles. Evite un `web.php` monolithique.
**Alternatives ecartees :** Tout dans web.php (ingerable a terme), route groups dans un seul fichier (moins lisible).
**Impact :** Toute nouvelle route doit aller dans le fichier de domaine correspondant. Les prefixes sont `v_beta/system/`, `v_beta/admin/`, `v_beta/`.

### [2026-04-25] Model Indicator polymorphe pour indicateurs multiples
**Decision :** Creer un model `Indicator` avec relation polymorphe `indicatorable` (morphMany) vers LogicalFramework, SpecificObjective et Result, au lieu de stocker les indicateurs comme champs texte uniques.
**Raison :** Le client (Projexia) demande N indicateurs par niveau, chacun avec sa propre source de verification et hypothese. Un champ longText ne peut pas gerer ca. La relation polymorphe evite de creer 3 tables separees (une par niveau).
**Alternatives ecartees :** JSON array dans une colonne (pas requetable, pas de FK, pas d'audit individuel), table par niveau (redondant, 3 tables identiques).
**Impact :** Les anciens champs texte (indicators, verification_sources, assumptions) sont conserves temporairement pour retrocompatibilite. Les formulaires et vues doivent etre adaptes pour gerer un array d'indicateurs. Le trait SyncsIndicators centralise la logique create/update/delete.

### [2026-04-25] Formats d'affichage du cadre logique via enum + partials Blade
**Decision :** Creer un enum `LogframeDisplayFormat` (table/tree/cards) et 3 partials Blade dans `logframe/format-*.blade.php`, avec un selecteur dans project-show qui switch via `@include`.
**Raison :** Le client (Projexia) demande plusieurs formats de presentation du cadre logique. Les 3 formats couvrent les besoins : matrice standard ONG, vue hierarchique, et vue fiches detaillees.
**Alternatives ecartees :** Composant Livewire dedie par format (overhead pour du pur affichage), template Blade unique avec conditions (illisible avec 3 formats), configuration par organisation dans la DB (premature, aucun besoin multi-tenant pour ca).
**Impact :** L'ajout d'un nouveau format = 1 case dans l'enum + 1 partial Blade. Le selecteur est cote client (pas de requete serveur sauf Livewire re-render). Les exports PDF gardent leurs propres templates independants.
