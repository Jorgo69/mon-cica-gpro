<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des membres.
     */
    public function viewAny(User $user): bool
    {
        if ($user->role === \App\Enums\AccountType::SYSTEM_ADMIN) return true;
        return $user->hasPermissionTo('manage-users');
    }

    /**
     * Détermine si l'utilisateur peut voir un membre spécifique.
     */
    public function view(User $user, User $model): bool
    {
        if ($user->role === \App\Enums\AccountType::SYSTEM_ADMIN) return true;
        return $user->hasPermissionTo('manage-users') && (string) $user->organization_id === (string) $model->organization_id;
    }

    /**
     * Détermine si l'utilisateur peut créer un membre.
     */
    public function create(User $user): bool
    {
        // Interdire au SYSTEM_ADMIN de créer des utilisateurs dans les organisations
        if ($user->role === \App\Enums\AccountType::SYSTEM_ADMIN) return false;
        
        return $user->hasPermissionTo('manage-users');
    }

    /**
     * Détermine si l'utilisateur peut modifier un membre.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->role === \App\Enums\AccountType::SYSTEM_ADMIN) return false;
        
        return $user->hasPermissionTo('manage-users') && (string) $user->organization_id === (string) $model->organization_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer un membre.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->role === \App\Enums\AccountType::SYSTEM_ADMIN) {
            return false; // Le Root ne doit pas supprimer d'utilisateurs directement
        }

        // Empêcher de se supprimer soi-même
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermissionTo('manage-users') && (string) $user->organization_id === (string) $model->organization_id;
    }
}
