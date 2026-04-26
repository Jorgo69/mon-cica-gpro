<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Invitation::class => \App\Policies\InvitationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Super Admin Bypass : ROOT a tous les droits
        // L'enum AccountType est la source de verite. On verifie aussi le role Spatie
        // IT_ADMIN dans le contexte global (team_id=null) pour eviter que l'impersonation
        // d'une org ne casse le bypass.
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user->role !== \App\Enums\AccountType::ROOT) {
                return null;
            }

            // Switch temporaire vers le contexte global pour verifier IT_ADMIN
            $currentTeam = getPermissionsTeamId();
            setPermissionsTeamId(null);
            $user->unsetRelation('roles'); // Vider le cache Spatie
            $hasGlobalRole = $user->hasRole('IT_ADMIN');

            // Restaurer le contexte
            setPermissionsTeamId($currentTeam);
            $user->unsetRelation('roles');

            return $hasGlobalRole ? true : null;
        });
    }
}
