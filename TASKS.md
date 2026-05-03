# Suivi du projet

## A faire — Roadmap complete

### CRITIQUE — Bloquant pour un premier client

- [x] **Phase 8 : Collaboration terrain** (TERMINEE)
  - [x] 8.1 Commentaires par activite (model Comment polymorphe, fil de discussion, mentions @user, notifications)
  - [x] 8.2 Pieces jointes par activite (upload fichiers sur activite/sous-activite, pas seulement projet)
  - [x] 8.3 Budget reel vs planifie (depenses reelles saisies, ecart planifie/reel, burn rate, alertes depassement automatiques)

- [x] **Phase 9 : Traductions i18n completes** (TERMINEE)
  - [x] 26 fichiers FR + 25 fichiers EN organises par domaine (common, auth, navigation, dashboard, projects, activities, budgets, resources, admin, settings, notifications, system, search, pdf, errors, comments, attachments, enums, mail, shared)
  - [x] 61 vues Blade migrees vers __()
  - [x] 9 enums avec label() bilingue via __('enums.*')
  - [x] 10 notifications traduites (toMail + toArray)

- [x] **Phase 10 : Tests automatises + CI** (TERMINEE)
  - [x] 234 tests, 462 assertions (Pest PHP)
  - [x] Tests Feature (auth, projets, invitations), Unit (services, enums), Policy (31 tests)
  - [x] GitHub Actions CI (lint PHP, tests Pest, build Vite)
  - [x] CD conditionne au succes du CI (workflow_run)

### IMPORTANT — Fait la difference avec la concurrence

- [x] **Phase 11 : Templates de projet** (TERMINEE)
  - [x] ProjectTemplateService (duplication complete projet + logframe + objectifs + resultats + activites + ressources + budgets + indicateurs)
  - [x] Flag is_template + source_project_id dans migration consolidee
  - [x] Bibliotheque de templates (grille cards, badges systeme/org, compteurs, recherche)
  - [x] Bouton Dupliquer sur project show + lien sidebar Templates

- [x] **Phase 12 : Export Excel** (TERMINEE)
  - [x] maatwebsite/excel ^3.1 installe
  - [x] 4 classes export : ProjectActivitiesExport, ProjectBudgetExport, ProjectIndicatorsExport, ProjectFullExport (multi-feuilles)
  - [x] Route GET /projects/{id}/export-excel/{type?} (full, activities, budget, indicators)
  - [x] Bouton Export Excel dans project show

- [x] **Phase 13 : Tableau de bord bailleur** (TERMINEE)
  - [x] ShareToken model + migration (token UUID 48 chars, expiration, label, compteur vues)
  - [x] Route publique /shared/project/{token} (sans auth)
  - [x] Dashboard read-only (progression, activites, budget, cadre logique, indicateurs)
  - [x] Livewire ProjectShareLivewire (creer/activer/desactiver/supprimer liens, copier URL)
  - [x] Layout standalone propre (pas de sidebar/navbar)

- [x] **Phase 14 : Visualisation timeline / Gantt** (TERMINEE)
  - [x] Diagramme Gantt horizontal pur Tailwind + Alpine.js (zero dependance)
  - [x] En-tete mois dynamique, barres colorees par statut, progression interne
  - [x] Marqueur "Aujourd'hui", tooltip hover, legende
  - [x] Nouvel onglet Timeline dans project show

- [x] **Phase 15 : Suivi des indicateurs** (TERMINEE)
  - [x] IndicatorMeasurement model + migration (value, comment, measured_at)
  - [x] current_value + unit sur Indicator, progressPercent(), trend()
  - [x] IndicatorTrackingLivewire (tableau, formulaire inline, modal historique)
  - [x] Onglet "Indicateurs" dans project show (remplace "Analyses")
  - [x] IndicatorAlertNotification (stagnation/regression)

### NICE-TO-HAVE — Polish produit

- [x] **Phase 16 : Onboarding guide** (TERMINEE)
  - [x] OnboardingService (etapes adaptees par role : ORG_ADMIN 4, MEMBER 3, INDEPENDENT 2)
  - [x] Composant onboarding-checklist dans le dashboard (anneau progression, etapes cliquables, dismiss)
  - [x] Projet demo seeder (Formation agriculteurs, cadre logique complet, marque template)
  - [x] Auto-tracking : visited_dashboard, visited_settings

- [x] **Phase 17 : Documentation utilisateur** (TERMINEE)
  - [x] Composant x-ui.help-tip (tooltip contextuel, 30+ cles aide FR+EN)
  - [x] Page FAQ Livewire (/faq, recherche, 6 categories, 17 questions FR+EN, accordeon)
  - [x] Lien "Aide / FAQ" dans la sidebar

- [x] **Phase 18 : RGPD / Protection des donnees** (TERMINEE)
  - [x] GdprExportService (export JSON complet : compte, projets, activites, commentaires, notifs)
  - [x] GdprDeleteService (anonymisation irreversible, desassociation activites)
  - [x] Pages publiques /privacy et /terms (7 sections chacune, FR+EN)
  - [x] Banniere cookies (Alpine.js, localStorage, accept/essential only)
  - [x] Section RGPD dans le profil (bouton export + liens legaux)

- [x] **Phase 19 : Performance & Cache** (TERMINEE)
  - [x] Cache dashboard stats 5 min par user+periode, CacheInvalidationObserver
  - [x] 16 index DB (activities, projects, comments, attachments, indicators, expenses, budgets, share_tokens)
  - [x] N+1 fix ProjectListLivewire (eager load creator + projectType)
  - [x] Deploy script optimise (clear all > migrate > rebuild caches)

- [x] **Phase 20 : SaaS Plans** (TERMINEE)
  - [x] Enum Plan (Free/Pro/Enterprise), config limites dans gpro.php
  - [x] Plans sur Organization ET User (independants), effectivePlan(), isPlanActive(), hasFeature()
  - [x] Middleware plan:feature (bloque si feature pas dans le plan, ROOT bypass)
  - [x] UI ROOT : dropdown changement plan par org en 1 clic
  - [x] Onglet Plan dans Settings (usage, expiration, CTA upgrade, contacts WhatsApp/email)
  - [x] Page /pricing publique (3 plans, comparatif features, paiement MoMo/virement)
  - [x] Paiement configurable : PAYMENT_GATEWAY_URL dans .env (FedaPay/Kkiapay ready)

- [x] **Phase 21 : PWA Multi-plateforme** (TERMINEE)
  - [x] manifest.json (standalone, icones, raccourcis)
  - [x] Service Worker (network-first, cache assets, page offline, push FCM)
  - [x] 8 icones PNG (72-512px)
  - [x] Page /offline
  - [x] Meta tags PWA dans layouts app + guest

### FUTUR — Apres v1.0

- [ ] Import Excel (bulk import activites, budgets, indicateurs)
- [ ] Collaboration temps reel (presence indicators, curseurs, Livewire polling ou WebSockets)
- [ ] Workflow d'approbation configurable (soumission → validation → approbation multi-niveaux)
- [ ] Rapports automatiques (generes automatiquement chaque trimestre, envoyes par email)
- [ ] Integration calendrier (Google Calendar, Outlook sync)
- [ ] Carte geographique des projets (si localisation GPS)
- [ ] IA : analyse automatique du cadre logique, suggestions d'amelioration, detection d'incoherences
- [ ] API publique complete (webhooks, OAuth2 pour integrations tierces)
- [ ] Marketplace de plugins/extensions

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
- [x] Phase 6.5 : Stabilisation + Settings -- Settings Livewire 3 onglets (Apparence/Langue/Notifications) branches UserMeta, sync bidirectionnelle theme navbar<->Settings, recherche globale Ctrl+K refaite (categories, securite, quick actions, historique), profil redesigne design system, systeme avatars (24 SVG predefinis + x-ui.avatar), CSRF fix, fetch credentials fix
- [x] Merge dev-ui-design -> development : resolution de ~57 fichiers en conflit + restauration 80 vues v-beta/
- [x] Phase 7.1 : Infrastructure Queue database + Timezone utilisateur (migration jobs/job_batches, scheduler Kernel, select timezone Settings, config gpro.timezones)
- [x] Phase 7.2 : Notifications enrichies -- Enum NotificationType (9 types), trait HasNotificationPreferences (via() dynamique), NotificationPreferenceService, 4 nouvelles notifs (DeadlineApproaching, ActivityOverdue, BudgetThreshold, WeeklyDigest), refactoring 4 notifs existantes (ShouldQueue + via dynamique), grille preferences par type dans Settings
- [x] Phase 7.3 : Rappels automatiques -- ReminderService (queries, dedup, escalade, digest), 3 commandes artisan (send-deadline-reminders J-7/3/1, send-overdue-alerts + escalade J+7, send-weekly-digest), scheduler Kernel configure
- [x] Phase 7.4 : Push notifications FCM -- laravel-notification-channels/fcm + firebase JS SDK, FcmToken model + migration, FcmTokenController (store/destroy), trait HasFcmNotification (toFcm generique), service worker + firebase-push.js, 7 notifs equipees FCM, degradation gracieuse
- [x] Phase 7.5 : Social Auth -- laravel/socialite, SocialAccount model + migration, SocialAuthService (login/register/link/invitation auto-accept), SocialAuthController, boutons Google/Facebook/Microsoft dans login+register (conditionnels), section "Comptes lies" dans Settings
- [x] Phase 7.6 : Multi-devise -- Enum Currency (8 devises), migration currency sur projects, ExchangeRate model + migration (taux manuels par org), CurrencyService (format/convert), composant x-currency, ExchangeRateManagementLivewire (CRUD admin), lien sidebar

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

### Session 8 (2026-04-26)
- Fix auth bloquee : double-hash password (cast `hashed` + Hash::make = double hash), sessions corrompues par acting_as_organization_id residuel.
- Fix Multitenantable : ROOT en impersonation se faisait exclure des queries User (organization_id=null vs acting org). Ajout orWhere pour ROOT lui-meme.
- Fix Gate::before : ROOT perdait hasRole('IT_ADMIN') dans contexte Spatie team org. Remplacement par check direct enum AccountType::ROOT.
- OrgSwitchController : passe de POST a GET (evite problemes CSRF/Livewire).
- Retrait #[Lazy] de TOUS les composants (16) : cause racine des erreurs "Snapshot missing" et "Could not find component in DOM tree".
- Service OrgContext cree : source unique de verite pour contexte org (orgId, isRoot, isImpersonating, mustFilter, canBypass, contextData).
- Multitenantable refactore : utilise OrgContext dans le creating event.
- Fix MemberManagement : parametre $id avant $queryService (injection Livewire).
- Phase 6 en cours : corrections globales, reorganisation routes, enrichissement Settings.

### Session 9 (2026-04-27)
- Tentative merge claude-suggestion → dev-ui-design : annulee (trop de conflits, doublon migrations).
- Branch fix/client-corrections creee depuis claude-suggestion (non utilisee, travail continue sur dev-ui-design).
- TipTap rich-editor integre : remplacement Summernote (composant Alpine + JS + deps npm).
- Visibilite 3 niveaux : is_system/is_active sur ProjectType, trait HasVisibilityScope, scope visibleForOrg().
- Audit automatise (2 sub-agents) : 6 bugs critiques identifies, 5 deja corriges dans des commits precedents.
- Fix conflit relation/colonne indicators : renommage en indicatorItems() sur 3 models (LF, SO, Result) + vues + PDF.
- Fix strtolower(enum) activity-list : utilise enum->label()/color().
- Fix MAIL_MAILER=log (Mailpit non lance en dev).
- Fix ProgressTracker : casts date/integer, null-safe relations, rattachement orphelins.
- Fix dropdown projets : x-teleport body + position fixed (overflow-hidden du parent).
- Fix AccountTypeMiddleware : redirect dashboard au lieu de 403 pour org_admin.
- Service UserMeta cree (get/set/forget, dot notation, JSON en DB).
- Migration meta JSON sur users + cast array sur User model.
- Traductions FR : validation, passwords, pagination.
- Fix toast vide : named params Livewire 3 + garde Alpine.
- Discussion architecture : Trait HasMeta generique, LogframeQueryService, DB-agnostic (SQLite→PostgreSQL), preparation Open Source.

### Session 10 (2026-04-28)
- Migration Laravel 10 → 12 (framework 12.58.0, Livewire 3.7.15, Sanctum 4.3, Carbon 3.11).
- UUIDv7 : Str::uuid() → Str::orderedUuid() dans 32+ fichiers.
- Carbon 3 : (int) cast sur diffInDays/diffInWeeks/diffInMonths.
- Swagger (l5-swagger) retire, annotations OpenAPI nettoyees dans 6 fichiers.
- PDF Studio installe (sarder/pdfstudio ^2.0), architecture multi-driver (DomPDF defaut + Chromium premium).
- Template PDF moderne refait : sobre, professionnel, noir/gris, marges aerees, sans gradient.
- Fix HTML rendering dans PDF ({{ }} → {!! strip_tags() !!} pour rich-text).
- Fix CRITIQUE : documents jamais sauvegardes dans submitForm() → Storage::store + ProjectDocument::create.
- Fix CRITIQUE : budgets jamais sauvegardes → Budget::create dans submitForm().
- Fix relation documents/projectDocuments unifiee, file_mime_type → file_type.
- Fix SyncsIndicators : indicators() → indicatorItems().
- Fix loadExistingProject : collect()->only() pour eviter colonnes parasites (indicator_items dans SQL).
- Fix activity status : 'En cours' → ActivityStatus::DRAFT->value (casse enum).
- Fix ProjectPolicy : createur peut modifier son brouillon meme sans permission edit-projects.
- Fix authorize('create') ajoute dans mount() du formulaire proposition.
- Enum AdminCategoryType (6 types : project_category, budget_category, resource_type, document_type, etc.).
- Seeder GeneralAdministration refait : 23 categories systeme reparties par type enum.
- CategoryQueryService + vue refaits : filtre par type, badge colore, protection is_system.
- Multitenantable : items is_system=true visibles par toutes les orgs (orWhere is_system).
- Route admin : ajout independent dans account_type middleware.
- AccountTypeMiddleware : bypass ROOT automatique (plus besoin de lister system_admin partout).

### Session 11 (2026-04-30)
- Merge dev-ui-design → development : resolution 57 fichiers en conflit + restauration 80 vues v-beta/.
- **Phase 7 complete** (6 sous-phases) :
  - 7.1 Infrastructure Queue database + Timezone utilisateur
  - 7.2 Notifications enrichies (enum NotificationType, preferences par type, 4 nouvelles notifs, refactoring via() dynamique + ShouldQueue)
  - 7.3 Rappels automatiques (3 commandes artisan, ReminderService, escalade, dedup, digest)
  - 7.4 Push FCM (firebase JS, service worker, FcmToken model, trait HasFcmNotification, degradation gracieuse)
  - 7.5 Social Auth (Socialite, Google/Facebook/Microsoft, SocialAccount model, auto-accept invitation, section Settings)
  - 7.6 Multi-devise (enum Currency 8 devises, ExchangeRate model, CurrencyService, composant x-currency, admin CRUD)
- Roadmap complete documentee : Phases 8-21 + backlog futur (23 chantiers identifies).
- **Phase 8 complete** (3 sous-phases) :
  - 8.1 Commentaires par activite (model Comment polymorphe, replies, mentions @user, CommentSectionLivewire reutilisable, CommentPostedNotification)
  - 8.2 Pieces jointes par activite (model Attachment polymorphe, AttachmentSectionLivewire, upload multi-fichiers, download, auto-delete fichier)
  - 8.3 Budget reel vs planifie (model Expense, BudgetTrackingService avec projectSummary/budgetLineSummaries/burnRate/spendingByCategory, ExpenseManagementLivewire, alerte BudgetThresholdNotification >= 80%)
- **Consolidation migrations** : 5 fichiers add_*/alter fusionnes dans les CREATE correspondants (users, projects, project_types, general_administrations, organizations, logical_frameworks, specific_objectives, results, activities). migrate:fresh --seed passe proprement (42 migrations).

### Session 12 (2026-05-01)
- Merge dev-ui-design → development : resolution conflits migrations (doublons 2026_03_24_* supprimes, seeders recuperes).
- Deploy fix : migrations orphelines identifiees et nettoyees pour le serveur LWS.
- **Phase 9 complete** : i18n (26 fichiers FR + 25 EN, 61 vues migrees, 9 enums bilingues, 10 notifs traduites).
- **Phase 10 complete** : Tests + CI (234 tests Pest, CI GitHub Actions, CD conditionne).
- **Phase 11 complete** : Templates de projet (ProjectTemplateService, duplication complete, bibliotheque UI, sidebar).
- **Phase 12 complete** : Export Excel (maatwebsite/excel, 4 exports, route, bouton show).
- **Phase 13 complete** : Tableau de bord bailleur (ShareToken, dashboard public read-only, gestion liens partage).
- **Phase 14 complete** : Gantt/Timeline (diagramme horizontal Tailwind+Alpine, onglet project show).
- Fix bonus : composant x-currency cree, enums ActivityStatus corrigees (ONGOING au lieu de IN_PROGRESS).
- CI/CD : deploy.yml conditionne au succes du CI (workflow_run).

### Session 13 (2026-05-02)
- **Bugfixes** (8 bugs) : login cookies corrompus, HTML dans Excel (strip_tags), dark mode persiste apres logout, Settings htmlspecialchars (EN settings.php structure alignee sur FR), timeline tooltip z-index, share link auto-copy, bouton template introuvable (ajoute dans project show), MEMBER sans create-projects (permission ajoutee).
- **Sidebar refactoree** : Projets en dropdown avec sous-items (Tous les projets + Templates).
- **Phase 15 complete** : Suivi indicateurs (IndicatorMeasurement, progressPercent, trend, onglet Indicateurs).
- **Phase 16 complete** : Onboarding guide (OnboardingService par role, checklist dashboard, projet demo).
- **Phase 17 complete** : Documentation (help-tip contextuel, page FAQ Livewire 17 questions, sidebar).
- **Phase 18 complete** : RGPD (export JSON, anonymisation, CGU/confidentialite, banniere cookies).
- **Phase 19 complete** : Performance (cache dashboard 5min, 16 index DB, N+1 fix, deploy script).
- **Phase 20 complete** : SaaS Plans adapte Afrique (Free/Pro/Enterprise, activation manuelle ROOT, pricing publique, paiement MoMo/gateway configurable, plans independants).
- **Phase 21 complete** : PWA (manifest, service worker, icones, page offline, meta tags).
- **Toutes les 21 phases sont terminees.** Projet pret pour v1.0.
- **IA Gemini/Groq integree** : boutons IA par champ (step 2-5), resume executif modale, analyse dashboard.
- **Fix resume IA** : modale au lieu d'inline (ne casse plus le layout).
- **Fix logique metier** : projet en brouillon bloque la progression/depenses (isOperational).
- **Google OAuth** : login/register avec compte Google (Socialite).

### Session 14 (2026-05-02)
- **Phase 22 complete** (8 sous-taches) : IA configurable multi-niveau
  - Enum AiProvider (5 providers : groq, gemini, openai, mistral, custom)
  - Model AiConfig polymorphe + migration (cles chiffrees encrypt/decrypt)
  - AiConfigResolver : cascade org config > global DB (ROOT) > .env > disabled
  - Refactor GeminiService → AiService (providers dynamiques, OpenAI-compatible + Gemini natif)
  - UI ROOT /system/ai-config (provider, cle masquee, test connexion, status .env)
  - UI ORG_ADMIN + INDEPENDENT : onglet IA dans Settings (3 modes, toggle par membre)
  - Middleware CheckAiAccess (bloque si IA indisponible)
  - Sidebar ROOT : lien Configuration IA
  - Traductions FR+EN completes (ai.config.*, navigation.ai_config, enums.ai_provider)

### Session 15 (2026-05-03)
- **Phase 22 enrichie** : 9 providers (+ Anthropic, DeepSeek, Cohere, Together), guides integres dans UI, 10 FAQ IA, support API Anthropic/Cohere natif
- **Branding org** : migration logo_path/website/contact_email/contact_phone/description, upload logo dans Settings, navbar co-branding, dashboard bailleur logo
- **Email invitation refait** : template HTML custom co-brande (logo org, role assigne, inviteur, footer pro)
- **Fix invitations** : tableau deborde corrige (x-ui.section pattern), 1 seul select role (member/manager/admin mapping auto), modale confirmation (x-ui.modal), cooldown 5min anti-spam, fix resend (revoke avant send)
- **Fix types projet** : categories query corrigee (project_category), badges Systeme/Global/Org, delete masque sur types systeme, toggleActive restreint ROOT, nom disabled sur types systeme
- **Composant x-ui.confirm-modal** reutilisable (Alpine.js)
- **Composant x-ui.input** : support prop disabled
- **Fix tests** : 234 tests OK (isOperational status ACTIVE, Spatie teamId null)
- **Planification** : Phases 23 (profil enrichi), 24 (owner + niveaux), 25 (RGPD), 26 (gpro:install)

## Phase 22 (TERMINEE) — IA configurable multi-niveau

- [x] 22.1 **Enum AiProvider** (groq, gemini, openai, mistral, custom) avec label/icon/defaultModel/baseUrl/isOpenAiCompatible
- [x] 22.2 **Model AiConfig** polymorphe (Organization ou User) + migration ai_configs, cles chiffrees encrypt()/decrypt()
- [x] 22.3 **Service AiConfigResolver** : cascade org config > global DB > global .env > disabled. Toggle par membre via UserMeta
- [x] 22.4 **Refactor GeminiService → AiService** : providers dynamiques, OpenAI-compatible (Groq/OpenAI/Mistral/Custom) + Gemini natif
- [x] 22.5 **UI ROOT /system/ai-config** : provider, cle masquee, modele, URL custom, toggle on/off, bouton test, status .env
- [x] 22.6 **UI ORG_ADMIN Settings → onglet IA** : 3 modes (global/own/disabled), config propre, toggle IA par membre
- [x] 22.7 **UI INDEPENDENT Settings → onglet IA** : meme interface conditionnel
- [x] 22.8 **Middleware CheckAiAccess** : bloque si IA non disponible (JSON ou abort 403)
- [ ] 22.9 **Tests** (a faire separement)

## Phase 23 (planifiee) — Profil enrichi

### Objectif
Pays/villes collaboratives, telephone formate par pays, oeil mot de passe.

### Taches

- [ ] 23.1 **JSON pays** : `config/countries.php` (250 pays, code ISO, nom FR/EN, prefixe tel, nb digits tel)
- [ ] 23.2 **Table cities** : migration (name, country_code, usage_count), Model City, autocompletion anonyme
- [ ] 23.3 **Migration profil** : ajouter `country_code`, `city` sur users
- [ ] 23.4 **Vue profil** : select pays + input ville autocompletion + telephone (prefixe auto selon pays, format dynamique)
- [ ] 23.5 **API autocompletion ville** : endpoint `/api/cities?q=&country=`, auto-creation si nouvelle
- [ ] 23.6 **Validation telephone** : regex dynamique selon pays (nb digits depuis config)
- [ ] 23.7 **Oeil mot de passe** : verifier toggle show/hide partout (login, register, profil, change password)

## Phase 24 (planifiee) — Owner org + Niveaux permissions

### Objectif
Separer owner/admin, niveaux visuels pour les permissions, protection du createur d'org.

### Architecture prevue

```
Niveaux :
  1. Observateur   → view-projects, view-activities (lecture seule)
  2. Contributeur  → + edit-activities, add-comments, track-progress
  3. Gestionnaire  → + create-projects, manage-activities, manage-budgets
  4. Administrateur → + manage-members, manage-settings, invite-users
```

### Taches

- [ ] 24.1 **Owner sur org** : migration `owner_user_id` sur organizations, relation, auto-assigne a la creation
- [ ] 24.2 **Transfert ownership** : UI dans Settings, seulement vers un autre admin, modale de confirmation
- [ ] 24.3 **Enum PermissionLevel** : 4 niveaux (observateur, contributeur, gestionnaire, administrateur) avec mapping permissions Spatie
- [ ] 24.4 **UI niveaux** : cartes visuelles dans membres + invitations (remplace select role brut)
- [ ] 24.5 **Protection owner** : ne peut pas etre supprime/retrograde, doit transferer avant de quitter
- [ ] 24.6 **Adaptation invitations** : utiliser PermissionLevel au lieu de spatie_role brut

## Phase 25 (planifiee) — RGPD complet

### Objectif
Export donnees par role, suppression/anonymisation, prevenance 30j, politique de retention.

### Regles

```
Suppression membre : anonymisation (nom→"Utilisateur supprime", email→hash), activites detachees
Suppression admin  : bloquer si seul owner, forcer transfert ownership
Suppression org    : email prevenance 30j → soft-delete → membres detaches → hard-delete apres 30j
Export membre      : ses donnees perso (profil, activites, commentaires, notifs) en JSON
Export admin       : ses donnees + export org (projets, membres, budgets, activites) en JSON+CSV
Export independant : ses donnees + ses projets en JSON
```

### Taches

- [ ] 25.1 **Export donnees perso** : refactorer GdprExportService, adapter par role (membre/admin/independant)
- [ ] 25.2 **Export org** (admin) : projets, membres, budgets, activites en JSON + CSV (ZIP)
- [ ] 25.3 **Suppression membre** : anonymisation, detachement activites
- [ ] 25.4 **Suppression admin** : bloquer si seul owner, forcer transfert
- [ ] 25.5 **Suppression org** : email prevenance 30j → soft-delete → membres detaches
- [ ] 25.6 **UI suppression** : section danger dans Settings (confirmation email + mot de passe)
- [ ] 25.7 **Politique retention** : commande `gpro:cleanup-deleted` (hard-delete orgs soft-deleted > 30j), scheduler

## Phase 26 (planifiee) — Commande gpro:install (Open Source)

### Objectif
Permettre une installation standalone sans ROOT. Pour la version Open Source.

### Taches

- [ ] 26.1 **Commande `php artisan gpro:install`** : interactive, cree la premiere org + premier ORG_ADMIN (owner), seed permissions/categories/types, configure .env
- [ ] 26.2 **Detection mode** : config `gpro.mode` = 'saas' (avec ROOT) ou 'standalone' (sans ROOT), conditionne la sidebar et les routes system
- [ ] 26.3 **Documentation** : README pour l'installation Open Source
