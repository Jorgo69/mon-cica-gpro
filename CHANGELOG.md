# Changelog

Toutes les modifications notables de ce projet sont documentees dans ce fichier.
Format base sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/).

## [2.0.0] - 2026-05-04

### Ajoute
- **E1 Import Excel** : import projets et activites, preview avant import, workflow 3 etapes
- **E2 Workflow approbation** : 10 statuts projet, transitions autorisees, WorkflowService, historique
- **E3 Rapports automatiques** : PDF/DOCX, commande trimestrielle, notifications
- **E4 Calendrier** : 6 vues (annee/semestre/trimestre/mois/semaine/jour), export iCal, sync Google/Outlook
- **E5 API REST v1** : 15 endpoints, auth Sanctum tokens, rate limiting
- **E6 Carte geographique** : Leaflet CDN, 40 pays geocodes, marqueurs par statut
- **E7 Webhooks** : HMAC-SHA256, 10 evenements, auto-disable apres 10 echecs
- **E8 WebSockets** : Laravel Reverb + Echo, 5 events, 4 channels prives + presence, OFF par defaut
- **E9 Marketplace plugins** : PluginManager, 8 hooks, plugin USAID exemple, commande artisan, catalogue remote
- Rename VBeta vers V1 (220 fichiers)
- Solidification : 462 tests (997 assertions), nettoyage vues orphelines, .env.example complet

### Corrige
- GdprDeleteService : meta NOT NULL crash
- GdprExportService : relation logicalFramework singulier
- Social auth : separation login/register (RGPD)
- Rate limiting sur LoginLivewire
- Selfhosted first user race condition (lockForUpdate)

## [1.0.0] - 2026-05-03

### Ajoute
- 32 phases de developpement completes
- Architecture multi-tenant (trait Multitenantable, isolation par organization_id)
- Cadre logique hierarchique (Objectif General > OS > Resultats > Activites > Sous-activites)
- RBAC 4 niveaux (ROOT, ORG_ADMIN, ORG_USER, INDEPENDENT) + Spatie Permission
- Dashboard avec stats temps reel et activites en retard
- Exports PDF (DomPDF/Chromium), Word (PHPWord), Excel (Maatwebsite)
- Systeme d'invitation (email + code + auto-accept)
- Notifications enrichies (database + mail + FCM) avec preferences par type
- Rappels automatiques (echeances, retards, digest hebdo)
- Social Auth (Google, Facebook, Microsoft)
- Multi-devise (8 devises, taux de change manuels)
- Commentaires et pieces jointes polymorphes
- Budget reel vs planifie avec alertes de depassement
- Templates de projet duplicables
- Tableau de bord bailleur public (lien partage avec token)
- Gantt/Timeline pur Tailwind + Alpine.js
- Suivi indicateurs (mesures, tendances, alertes)
- Onboarding guide par role
- FAQ integree (17 questions)
- RGPD complet (export, anonymisation, suppression planifiee)
- Cache dashboard + 16 index DB
- SaaS Plans (Free/Pro/Enterprise) en DB avec CRUD ROOT
- PWA (manifest, service worker, mode hors-ligne)
- IA configurable (9 providers, cascade org > global > .env)
- GPRO_MODE saas/selfhosted
- Profil enrichi (pays, ville, telephone)
- Owner organisation + transfert
- PermissionLevel (4 niveaux granulaires)
- Commande gpro:install
- i18n FR + EN complet
- 366 tests, 727 assertions
