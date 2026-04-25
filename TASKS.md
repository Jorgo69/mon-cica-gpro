# Suivi du projet

## En cours

(Aucune tache en cours)

## A faire

### Phase 1 -- COMPLETE

### Phase 2 -- Solidification SaaS
- [x] 2.1 Lazy loading + skeleton loaders sur 14 composants liste (#[Lazy] + placeholder skeleton-table)
- [ ] 2.2 Gestion connexion faible (offline detection, retry, feedback)
- [ ] 2.3 Pagination API endpoints
- [x] 2.4 Accessibilite (modal: role=dialog/aria-modal/aria-labelledby/focus, input/select: aria-required/aria-invalid/aria-describedby, toast: aria-live/role=status, errors: role=alert)
- [ ] 2.5 Coherence design system (478 occurrences hardcodees dans 32 fichiers -- session dediee recommandee)

## Termine
- [x] Setup initial Laravel 10 avec Breeze (auth, profil, password reset)
- [x] Multi-tenancy par organisation (trait Multitenantable, middleware SetOrganizationContext)
- [x] Modele de donnees complet (Organizations, Users, Projects, LogicalFramework, SpecificObjectives, Results, Activities, Resources, Budgets, QuarterlyBudgets, ProgressTrackers, QualitativeEvaluations)
- [x] Systeme RBAC complet (Spatie Permission + Enums AccountType)
- [x] Cadre logique hierarchique (Objectif General > Objectifs Specifiques > Resultats > Activites > Sous-activites)
- [x] Dashboard principal avec stats et activites en retard (Livewire, lazy loading)
- [x] CRUD Projets (creation, edition, liste, detail, corbeille)
- [x] Gestion des activites et sous-activites (hierarchie parent/children, statuts, progression)
- [x] Gestion des ressources et budgets (par activite et par projet, trimestriel)
- [x] Suivi de progression (ProgressTracker avec justification et scores)
- [x] Evaluations qualitatives
- [x] Systeme d'export PDF (Browsershot, templates modern/classic)
- [x] Systeme d'export Word/DOCX (PHPWord)
- [x] API REST avec Sanctum (projets) + documentation Swagger
- [x] Recherche globale (Ctrl+K) via GlobalSearchLivewire
- [x] Centre de notifications (composant Livewire, pas encore de vue)
- [x] Historique d'audit complet (Spatie Activity Log)
- [x] Gestion admin : organisations, roles, permissions, membres, categories, types de projet
- [x] Bibliotheque de composants UI (button, card, badge, stat-card, modal, table, skeleton, etc.)
- [x] Pages d'erreur personnalisees (400-503)
- [x] Helpers : excerpt_words, format_date, friendly_date, notify()
- [x] Support i18n (fr/en, changement de locale)
- [x] Onboarding utilisateur (creation d'organisation)
- [x] Champs dynamiques par projet (DynamicProjectField)
- [x] Phase 0 securite : dd() supprime, webhook securise (POST+header), CORS restreint, Sanctum expiration 24h, validation MIME uploads, sanitization XSS (mews/purifier clean()), migration Font Awesome vers Lucide Icons
- [x] Obs.1 : Type de projet rendu optionnel (migration originale modifiee, validation nullable, vue sans required)
- [x] Obs.2 : Indicateurs multiples complet (Model Indicator polymorphe, SyncsIndicators, formulaires creation+edition repetables aux 3 niveaux, affichage project-show + PDF modern/classic)
- [x] Obs.3 : Formats cadre logique (enum LogframeDisplayFormat, 3 partials : matrice/arborescence/fiches, selecteur dans project-show)
- [x] Obs.4 : Notifications completes (vue centre notifs, 4 classes notification database+mail, triggers : soumission projet, assignation activite, changement statut, progression activite)
- [x] Obs.5 : Suivi narratif (ProgressTracker auto dans UpdateActivityProgressAction, timeline historique ActivityProgressHistoryLivewire, dashboard comparatif previsions/realisations ProjectProgressComparisonLivewire, tab Suivi dans project-show)

---

## Historique des sessions

### Session initiale (2026-04-25)
- Kit claude-starter installe et adapte au projet existant.
- Analyse complete du code existant et documentation des taches deja realisees.

### Session 2 (2026-04-25)
- Audit complet : securite backend, performance frontend, design system.
- Phase 0 securite terminee (7 corrections critiques).
- Retours client Projexia analyses et planifies en Phase 1.
- Phase 1 demarree : Obs.1 terminee, Obs.2 fondations posees (model Indicator + actions).
- 2.3 formulaire step-3 avec champs repetables pour indicateurs (ProposalProjectFormLivewire + vue).
- 2.4 EditProjectDesignLivewire adapte : indicateurs repetables aux 3 niveaux (logframe, objectif, resultat) + SyncsIndicators dans submit.
- 2.5 Affichage indicateurs polymorphes dans project-show + PDF modern/classic.
- Obs.2 (indicateurs multiples) entierement terminee.
- Obs.3 (formats cadre logique) terminee : enum, 3 partials, selecteur dans project-show.
- Obs.4 (notifications) terminee : vue dropdown, 4 notifications (database+mail), 4 triggers.
- Obs.5 (suivi narratif) terminee : ProgressTracker auto, timeline historique, dashboard comparatif, tab Suivi.
- **Phase 1 complete** -- toutes les 5 observations client traitees.
