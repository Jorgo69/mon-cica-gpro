# Suivi du projet

## En cours

(Aucune tache en cours)

## A faire

(Aucune tache planifiee)

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
- [x] Phase 2.1 : Lazy loading + skeleton sur 14 composants liste (#[Lazy] + x-ui.skeleton-table)
- [x] Phase 2.2 : Offline banner (x-ui.offline-banner, Alpine.js navigator.onLine, integre dans app.blade.php)
- [x] Phase 2.3 : Pagination API (paginate + search + status sur ProjectApiController, show avec eager loading)
- [x] Phase 2.4 : Accessibilite (modal: role=dialog/aria-modal/focus, input/select: aria-required/aria-invalid/aria-describedby, toast: aria-live, errors: role=alert)
- [x] Fix auth/onboarding : migration role nullable, enums partout (plus de strings), Multitenantable gere role=null, bouton deconnexion onboarding, Schema::getColumnListing remplace par getFillable
- [x] Phase 2.5 : Coherence design system -- tokens semantiques CSS vars (heading, body, subtle, muted, surface, surface-alt, card, border, border-light, warning, info) + migration de 2965/2969 occurrences hardcodees vers tokens (32+ fichiers Blade + app.css)
- [x] Phase 3.1 : Refonte RBAC -- SYSTEM_ADMIN renomme ROOT (valeur DB inchangee), ROOT sans org (organization_id=null), permissions invite-users + manage-invitations, SaveMemberAction accepte spatie_role explicite
- [x] Phase 3.2 : Isolation stricte cross-org -- UserQueryService centralise, 8 composants Livewire corriges (plus de User::all()), Multitenantable renforce avec Schema::hasColumn() pour INDEPENDENT
- [x] Phase 3.3 : Systeme d'invitation complet -- Model Invitation (UUID, token+code, expiration 7j), enum InvitationStatus, 3 Actions (Send/Accept/Revoke), InvitationNotification (mail avec lien+code), InvitationController (3 scenarios : connecte/login/register), integration onboarding joinOrganization() par code, auto-accept post-register et post-login via session token
- [x] Phase 3.4 : Interface admin invitations -- InvitationManagementLivewire (liste paginee, envoi, renvoi, revocation, filtres statut), InvitationPolicy, route admin, lien sidebar

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

### Session 3 (2026-04-25)
- Phase 2.1-2.4 terminee (lazy loading, offline, pagination API, accessibilite).
- Fix auth/onboarding : role nullable, enums partout, Multitenantable robuste, bouton logout onboarding.
- Reste : 2.5 coherence design system (478 occurrences dans 32 fichiers).

### Session 4 (2026-04-25)
- Phase 2.5 terminee : coherence design system.
- Tokens semantiques ajoutes dans app.css (CSS vars) : heading, body, subtle, muted, surface, surface-alt, card, border, border-light, warning, info + variantes dark.
- tailwind.config.js mis a jour : toutes les couleurs mappees sur CSS vars (plus de hex hardcodes).
- 2965/2969 occurrences de couleurs hardcodees migrees vers tokens semantiques dans 80+ fichiers Blade.
- Composants CSS (nav-item, nav-dropdown, navbar-action, glass-card, sidebar) migres vers tokens.
- Build prod OK, CSS reduit (121.64 KB vs 121.74 KB original).
- **Phase 2 complete** -- toutes les taches de solidification SaaS terminees.

### Session 5 (2026-04-26)
- Refonte RBAC : SYSTEM_ADMIN renomme ROOT dans l'enum (valeur DB inchangee `system_admin`), find-replace 14 fichiers.
- ROOT sans organisation (organization_id=null dans seeder, middleware bypass inchange).
- Isolation cross-org : UserQueryService cree, 8 composants Livewire corriges (User::all() -> UserQueryService::forCurrentOrg()), Multitenantable renforce (Schema::hasColumn au lieu de getFillable).
- Permissions d'invitation ajoutees (invite-users, manage-invitations) sur IT_ADMIN et ORG_ADMIN.
- SaveMemberAction : accepte spatie_role explicite (permet multi org_admin + choix de role).
- Systeme d'invitation complet : model Invitation, enum InvitationStatus, migration, 3 Actions (Send/Accept/Revoke), InvitationNotification (mail), InvitationController (lien public), route /invitation/{token}.
- Integration dans les 3 flows auth : onboarding (code), register (token session auto-accept), login (token session auto-accept).
- Interface admin : InvitationManagementLivewire + vue + InvitationPolicy + sidebar.
- **Phase 3 complete** -- RBAC ROOT + isolation cross-org + systeme d'invitation.

### Session 6 (2026-04-26)
- Performance : wire:navigate retire (cause Snapshot missing Livewire), jQuery/Summernote retire du layout global, N+1 budget dashboard corrige, layout HTML nettoye.
- Phase 4.1 : Escalade de privileges bloquee -- AccountType::assignableRoles(), SaveMemberAction verifie les roles assignables, Gate::before double-check enum+Spatie, formulaire membre n'affiche que les roles autorisés.
- Phase 4.2 : 5 fichiers corriges -- comparaisons enum/string cassees (DashboardLivewire, ProjectStatsQueryService, ActivityQueries, ActivityHistoryLivewire, SubActivityPolicy).
- Phase 4.3 : authorize() restaures -- ProjectShowLivewire, EditProjectDesignLivewire (AuthorizesRequests ajoute).
- Phase 4.4 : GlobalSearchService simplifie -- fait confiance au Global Scope, INDEPENDENT peut chercher ses propres donnees. ActivityHistoryLivewire filtre par causer_id pour INDEPENDENT.
- Phase 4.5 : hasRole('IT_ADMIN') elimine partout sauf Gate::before (double-check). MemberQueryService simplifie (fait confiance au Global Scope).
- Phase 4.6 : Dashboard ROOT cree -- RootDashboardLivewire (stats globales, top orgs, derniers inscrits, invitations), route system.dashboard, redirection auto depuis /dashboard pour ROOT, lien sidebar.
- Phase 4.7 : Systeme email -- Model EmailSuppression (bounce/unsubscribe/complaint), listeners MessageSending/MessageSent, EmailUnsubscribeController (tokens HMAC signes), routes unsubscribe/resubscribe, vues confirmation, lien unsubscribe dans InvitationNotification, .env.example pret Gmail SMTP.
- **Phase 4 complete** -- Etancheite multi-tenancy + dashboard ROOT + systeme email.
- Seeder adapte : 2 orgs (Projexia + ONG Espoir), 7 comptes test (ROOT, 2 org_admin, 2 org_user, 1 member, 1 independent).

### Session 7 (2026-04-26)
- Phase 5.1 : Mecanisme "Entrer dans une org" -- OrgSwitchController (enter/leave), session acting_as_organization_id, SetOrganizationContext adapte, Multitenantable adapte (ROOT en impersonation filtre par org cible), DashboardLivewire conditionnel.
- Phase 5.2 : Bandeau rouge impersonation -- composant org-impersonation-banner, affiche en haut de page avec bouton Quitter, pt-8 sur le wrapper quand actif.
- Phase 5.3 : Sidebar ROOT dediee -- sidebar-root.blade.php (Supervision, Organisations, Utilisateurs, Emails, Roles, Audit, Profil), switch conditionnel dans app.blade.php. ROOT en impersonation voit la sidebar org.
- Phase 5.4 : Fix skeleton dashboard ROOT -- #[Lazy] retire (composant leger, chargement direct).
- Phase 5.5 : Page Organisations ROOT -- RootOrganizationListLivewire (liste toutes orgs, withCount users/projects, recherche, activer/suspendre, bouton Entrer via form POST).
- Phase 5.6 : Page Utilisateurs ROOT -- RootUserListLivewire (withoutGlobalScopes, filtres org/role, bloquer/debloquer via email_verified_at, reset password via Password::sendResetLink).
- Phase 5.7 : Page Emails ROOT -- RootEmailSuppressionLivewire (suppression list, stats bounced/unsubscribed/complained, ajout/retrait manuel).
- Phase 5.8 : Audit ROOT -- integre dans OrgSwitchController (Spatie Activity Log sur chaque enter/leave, visible par l'org_admin).
- **Phase 5 complete** -- Refonte experience ROOT (sidebar dediee, impersonation org, 4 pages admin).
