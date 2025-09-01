<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Si le projet est en brouillon : seul l'administrateur peut voir
        // if ($project->status === 'Brouillon') {
        //     return $user->role === 'Administrateur';
        // }
        if (Str::lower($project->status) === 'brouillon') {
            return $user->role === 'Administrateur';
        }
        

        // Si le projet n'est pas en brouillon : créateur ou administrateur peuvent voir
        return $user->role === 'Administrateur' || $project->creator_user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Pour la création, on peut autoriser tous les utilisateurs authentifiés, ou restreindre davantage si nécessaire.
        // Ici, nous autorisons tous les utilisateurs authentifiés à créer.
        return $user->exists(); // Assure que l'utilisateur est authentifié
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // dd($user->id);
        // L'utilisateur peut mettre à jour si c'est l'utilisateur responsable OU s'il est administrateur.
        return $user->id === $project->creator_user_id || $user->role === 'Administrateur';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // L'utilisateur peut supprimer si c'est l'utilisateur responsable OU s'il est administrateur.
        return $user->role === 'Administrateur';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        // L'utilisateur peut restaurer s'il est administrateur.
        return $user->role === 'Administrateur';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        // L'utilisateur peut restaurer s'il est administrateur.
        return $user->role === 'Administrateur';
    }
}
