# Journal des bugs

Bugs rencontres et leurs solutions. A consulter AVANT de debugger un symptome qui semble familier.

---

## Modele d'entree

### [AAAA-MM-JJ] Titre court du symptome
**Symptome :** ce qu'on voyait.
**Cause :** la vraie cause identifiee.
**Solution :** ce qui a corrige.
**Prevention :** comment eviter a l'avenir.

---

## Bugs resolus

### [2026-04-25] Suppression de projet ne fonctionne pas (dd() oublie)
**Symptome :** Cliquer sur "Supprimer" un projet affiche une page blanche avec les donnees brutes du projectId.
**Cause :** `dd($projectId)` oublie dans `ProjectListLivewire::deleteProject()` (ligne 59). De plus, `$this->authorize('delete', $this->projectId)` utilisait la mauvaise variable (propriete au lieu du parametre).
**Solution :** Supprime le dd(), remplace par `Project::findOrFail($projectId)` + `$this->authorize('delete', $project)` + `$project->delete()`.
**Prevention :** Ne jamais laisser de `dd()` dans le code. Utiliser `/check` avant chaque commit.

### [2026-04-25] Webhook expose des infos sensibles et accepte GET
**Symptome :** Le webhook de deploiement acceptait les requetes GET avec le token en URL, loggait le PATH et le user systeme, et renvoyait l'output complet du deploy.
**Cause :** Design initial sans securite (token en query string, pas de validation de methode HTTP, `hash_equals` non utilise).
**Solution :** POST only, token via header Authorization/X-Webhook-Token, `hash_equals()` pour la comparaison, `escapeshellarg()` sur le path, reponse sans output sensible.
**Prevention :** Les webhooks doivent toujours utiliser POST + token en header + HMAC si possible.

### [2026-04-25] Impossible de se connecter avec les comptes seedes
**Symptome :** Login avec alice.d@cpro.org / password echoue. Tous les comptes du seeder sont inaccessibles.
**Cause :** Double probleme. (1) `firstOrCreate` ne met pas a jour les champs si l'email existe deja. Si le seeder tourne une premiere fois sans l'org `cica-pro` (qui n'existe pas encore), les users sont crees avec `organization_id = NULL` et `role = NULL`. Les runs suivants ne corrigent pas. (2) Le trait `Multitenantable` bloquait tout user ayant `organization_id = NULL` et `is_independent = false` via `whereRaw('1 = 0')`, rendant meme le login post-connexion inutilisable.
**Solution :** (1) `php artisan migrate:fresh --seed` pour repartir de zero. (2) Fix du scope Multitenantable : les independants (`is_independent = true`) voient leurs propres donnees via `creator_user_id` au lieu d'etre bloques.
**Prevention :** Utiliser `updateOrCreate` au lieu de `firstOrCreate` dans les seeders quand les champs doivent etre mis a jour. Tester le login apres chaque seed. Prevoir le cas des users sans organisation dans le scope multi-tenant.

### [2026-04-25] "member" is not a valid backing value for enum AccountType
**Symptome :** ValueError a l'inscription ou au login. L'enum AccountType plante car la valeur en DB ne correspond a aucun case.
**Cause :** Triple probleme. (1) Migration users avait `default('member')` mais l'enum n'a que system_admin/org_admin/org_user/independent. (2) CreateOrganizationAction ecrivait `'role' => 'admin'` (string) au lieu de l'enum. (3) OnboardingLivewire::selectIndependent() n'assignait pas le role.
**Solution :** (1) Migration `role` nullable (pas de default — role assigne a l'onboarding). (2) CreateOrganizationAction utilise `AccountType::ORG_ADMIN`. (3) selectIndependent() assigne `AccountType::INDEPENDENT`. (4) User::saving() convertit les strings vides en null. (5) Multitenantable gere role=null (user en onboarding).
**Prevention :** Toujours utiliser les enums PHP, jamais de strings en dur pour les colonnes castees. Ajouter un guard saving() sur les colonnes enum nullable.

### [2026-04-25] Schema::getColumnListing dans le scope Multitenantable (perf)
**Symptome :** Lenteur potentielle sur chaque requete Eloquent pour les independants.
**Cause :** `Schema::getColumnListing()` fait une requete DB a chaque appel du scope global.
**Solution :** Remplace par `$builder->getModel()->getFillable()` (deja en memoire, pas de requete).
**Prevention :** Ne jamais appeler Schema:: dans un global scope. Utiliser les metadonnees du model (fillable, casts, etc.).
