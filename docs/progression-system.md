Elle explique **tout** :  
→ le **pourquoi**,  
→ le **comment**,  
→ les **méthodes**,  
→ les **hypothèses métier**,  
→ et l’**utilisation concrète**.

---

# 📄 Système de Calcul de Progression – Documentation

> **Fichier :** `progression-system.md`  
> **Objectif :** Comprendre le système de progression des activités et du projet, basé sur les sous-activités, sans champ `status` fixe.

---

## 🎯 Objectif du système

Ce système permet de **calculer dynamiquement la progression** :
- D’une **activité**, en fonction de l’état de ses **sous-activités**
- D’un **projet**, en fonction de la progression de ses **activités**

Il repose sur une **logique métier cohérente**, **sans duplication de données**, et **sans champ `status` statique** sur les modèles `Activity` ou `Project`.

Tout est **calculé à la volée**, ce qui garantit une **vue toujours à jour** de l’avancement réel du travail.

---

## 🧱 Structure hiérarchique

```
Projet
└── Activités
    └── Sous-activités
```

- Les **sous-activités** sont le niveau opérationnel.
- Les **activités** agrègent la progression de leurs sous-activités.
- Le **projet** agrège la progression de ses activités.

> ✅ Aucun champ `status` n’est stocké sur `Activity` ou `Project`.  
> ✅ Tout est déduit à partir des données réelles.

---

## 🔧 Méthodes clés

### 1. `Activity::calculateProgress(): float`

Calcule la progression d’une activité en fonction de ses **sous-activités**.

#### 🔎 Logique
- Chaque sous-activité a un statut : `'En Cours'` ou `'Terminé'`
- On attribue un **poids** à chaque statut :
  - `'En Cours'` → 50%
  - `'Terminé'` → 100%
- La progression de l’activité est la **moyenne des poids** de ses sous-activités.

#### 💡 Pourquoi ?
- `'En Cours'` = travail en cours → 50% d’effort estimé
- `'Terminé'` = travail fini → 100%
- Cela donne une **progression plus réaliste** qu’un simple comptage binaire.

#### 📦 Code

```php
public function calculateProgress(): float
{
    $subActivities = $this->subActivities;

    if ($subActivities->isEmpty()) {
        return 0.0;
    }

    $statusWeight = [
        'En Cours' => 50,
        'Terminé'  => 100,
    ];

    $totalProgress = $subActivities->sum(function ($subActivity) use ($statusWeight) {
        return $statusWeight[$subActivity->status] ?? 0;
    });

    $average = $totalProgress / $subActivities->count();

    return round($average, 2);
}
```

#### ✅ Utilisation
```php
$activity->calculateProgress(); // → ex: 75.0
```

---

### 2. `Activity::getPlannedProgressPercentage(): float`

Calcule la **progression attendue** selon le calendrier de l’activité.

#### 🔎 Logique
- Basée sur `start_date` et `end_date`
- Calcule la progression linéaire dans le temps
- Permet de détecter si une activité est **en retard**

#### 💡 Pourquoi ?
- Pour comparer la **progression réelle** vs **progression planifiée**
- Pour afficher un indicateur de **retard** sans intervention manuelle

#### 📦 Code

```php
public function getPlannedProgressPercentage(): float
{
    if (! $this->start_date || ! $this->end_date) {
        return 0.0;
    }

    $startDate = \Carbon\Carbon::parse($this->start_date);
    $endDate = \Carbon\Carbon::parse($this->end_date);
    $today = now();

    if ($today->lt($startDate)) {
        return 0.0; // Pas encore commencé
    }

    if ($today->gte($endDate)) {
        return 100.0; // Déjà terminé ou en retard
    }

    $totalDuration = $startDate->diffInDays($endDate);
    $elapsed = $startDate->diffInDays($today);

    if ($totalDuration === 0) {
        return 100.0;
    }

    return round(($elapsed / $totalDuration) * 100, 2);
}
```

#### ✅ Utilisation
```php
$activity->getPlannedProgressPercentage(); // → ex: 60.0
```

---

### 3. `Project::calculateProjectProgress(): float`

Calcule la progression du projet comme **la moyenne des progressions de ses activités**.

#### 🔎 Logique
- Récupère toutes les activités du projet via `getAllActivities()`
- Applique `calculateProgress()` à chaque activité
- Fait la **moyenne simple** des résultats

> ⚠️ Cette méthode suppose que `getAllActivities()` retourne une `Collection` d’objets `Activity`.

#### 📦 Code

```php
public function calculateProjectProgress(): float
{
    $activities = $this->getAllActivities();

    if ($activities->isEmpty()) {
        return 0.0;
    }

    $totalProgress = $activities->sum(fn($activity) => $activity->calculateProgress());

    return round($totalProgress / $activities->count(), 2);
}
```

#### ✅ Utilisation
```php
$project->calculateProjectProgress(); // → ex: 42.5
```

---

## 📊 Indicateurs de suivi (dans la vue)

Ces compteurs sont calculés à partir de la progression réelle et des dates.

```php
@php
    $totalActivitiesCount = $allActivities->count();

    $completedActivitiesCount = $allActivities->filter(fn($a) => $a->calculateProgress() >= 100)->count();

    $ongoingCount = $allActivities->filter(fn($a) => $a->calculateProgress() > 0 && $a->calculateProgress() < 100)->count();

    $nonStartedCount = $allActivities->filter(fn($a) => $a->calculateProgress() === 0)->count();

    $lateActivitiesCount = $allActivities->filter(function ($activity) {
        $planned = $activity->getPlannedProgressPercentage();
        $actual = $activity->calculateProgress();

        return $actual < $planned && $planned > 0;
    })->count();
@endphp
```

#### 📌 Signification :
- **Terminées** : progression ≥ 100%
- **En cours** : 0% < progression < 100%
- **Non démarrées** : progression = 0%
- **En retard** : progression réelle < progression planifiée

---

## 🧭 Hypothèses métier

| Hypothèse | Détail |
|---------|--------|
| Statuts des sous-activités | Seulement `'En Cours'` et `'Terminé'` |
| Poids des statuts | `'En Cours'` = 50%, `'Terminé'` = 100% |
| Progression linéaire | L’avancement est supposé uniforme dans le temps |
| Pas de `status` fixe | Tout est calculé dynamiquement |
| Dates obligatoires | `start_date` et `end_date` sont nécessaires pour le suivi de retard |

---

## 🛠️ Bonnes pratiques

- ✅ **Ne jamais stocker `progress_percentage` en base** → toujours calculé
- ✅ **Utiliser des méthodes claires** → `calculateProgress()`, pas `getFoo()`
- ✅ **Éviter les filtres sur `status`** → car il n’existe plus
- ✅ **Afficher les dates** dans l’interface → pour comprendre les retards
- ✅ **Mettre en cache** si performance devient un souci (via `Cache::remember`)

---

## 🔮 Évolutions possibles

- Ajouter d’autres statuts : `'Brouillon'` → 0%, `'En Attente'` → 10%
- Ponderer par importance des activités
- Ajouter un champ `weight` ou `priority`
- Générer un graphique de progression dans le temps

---

## ✅ Conclusion

Ce système permet de :
- Avoir une **vue précise** de l’avancement
- **Éviter la maintenance manuelle** des statuts
- **Détecter automatiquement** les retards
- **S’adapter facilement** à de nouveaux cas métier

> 📌 **À retenir** :  
> La progression est **dérivée**, **pas stockée**.  
> Elle reflète **le travail réel**, pas un état arbitraire.

---

📝 **Dernière mise à jour :** `{{ date('Y-m-d') }}`  
👨‍💻 **Auteur :** Toi (ou ton nom)

---

✅ Tu peux maintenant copier ce fichier dans ton projet, et y revenir à tout moment pour comprendre **le système de progression** sans avoir à relire tout le code.

Tu veux que je t’aide à générer une version **PDF** ou **HTML** pour documentation externe ?