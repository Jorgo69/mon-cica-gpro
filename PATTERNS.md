# Patterns de code du projet

Bouts de code recurrents a reutiliser pour garder un style coherent.
Claude consulte ce fichier avant d'ecrire du code qui ressemble a un pattern existant.

---

## Modele d'entree

### Nom du pattern
**Quand l'utiliser :** contexte.
**Exemple :**
```
// code ici
```
**A eviter :**
```
// ce qu'il ne faut pas faire
```

---

## Patterns

### Model Eloquent avec UUID + Multi-tenancy + Audit
**Quand l'utiliser :** Pour tout nouveau model metier lie a une organisation.
**Exemple :**
```php
<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MonModel extends Model
{
    use HasFactory, SoftDeletes, Multitenantable, LogsActivity;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'organization_id',
        'creator_user_id',
        // ... autres champs
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }
}
```
**A eviter :**
```php
// Ne pas utiliser d'auto-increment
protected $primaryKey = 'id';
public $incrementing = true; // FAUX

// Ne pas oublier le trait Multitenantable si le model a un organization_id
// Ne pas oublier LogsActivity sur les models metier
```

---

### Enum avec label/color/icon pour l'UI
**Quand l'utiliser :** Pour tout statut ou type affiche dans l'interface.
**Exemple :**
```php
<?php

namespace App\Enums;

enum MonStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::ACTIVE => 'En cours',
            self::COMPLETED => 'Termine',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::ACTIVE => 'blue',
            self::COMPLETED => 'green',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'pencil',
            self::ACTIVE => 'play',
            self::COMPLETED => 'check-circle',
        };
    }
}
```
**A eviter :**
```php
// Ne pas utiliser de strings en dur pour les statuts
$project->status = 'active'; // FAUX
$project->status = ProjectStatus::ACTIVE; // CORRECT

// Ne pas mettre les labels/couleurs dans les vues Blade
// Utiliser $status->label(), $status->color() depuis l'enum
```

---

### Composant Livewire avec pagination et notifications
**Quand l'utiliser :** Pour tout composant qui affiche une liste paginee et a besoin de feedback utilisateur.
**Exemple :**
```php
<?php

namespace App\Livewire\V1\MonDomaine;

use App\Livewire\Traits\WithToastNotifications;
use Livewire\Component;
use Livewire\WithPagination;

class MonListLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $items = MonModel::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->paginate(10);

        return view('livewire.v1.mon-domaine.mon-list-livewire', compact('items'));
    }
}
```
**A eviter :**
```php
// Ne pas charger toutes les donnees sans pagination
$items = MonModel::all(); // FAUX pour les listes

// Ne pas nommer le composant sans le suffixe Livewire
class MonList extends Component // FAUX
class MonListLivewire extends Component // CORRECT
```
