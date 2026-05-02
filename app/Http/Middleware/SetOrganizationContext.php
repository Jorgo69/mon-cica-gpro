<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetOrganizationContext
{
    /**
     * Garantit que l'utilisateur accede au bon contexte organisationnel.
     *
     * Pour ROOT : si acting_as_organization_id est en session, on utilise ce contexte.
     * Sinon ROOT bypass tout (pas de contexte restrictif).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // 1. ROOT : soit dans une org (impersonation), soit libre
            if ($user->role === \App\Enums\AccountType::ROOT) {
                // Nettoyer l'impersonation si la session est corrompue
                $actingOrgId = session('acting_as_organization_id');
                if ($actingOrgId) {
                    $orgExists = \App\Models\Organization::where('id', $actingOrgId)->exists();
                    if ($orgExists) {
                        setPermissionsTeamId($actingOrgId);
                    } else {
                        session()->forget(['acting_as_organization_id', 'acting_as_organization_name']);
                    }
                }
                return $next($request);
            }

            // 2. Gestion de l'Onboarding (Si aucune organisation rattachee et non independant)
            if (!$user->organization_id && !$user->is_independent) {
                $onboardingRoutes = ['onboarding', 'logout'];
                $isLivewireRequest = $request->hasHeader('X-Livewire');

                if (!$request->routeIs($onboardingRoutes) && !$isLivewireRequest) {
                    return redirect()->route('onboarding');
                }
                return $next($request);
            }

            // 3. Si l'organisation est suspendue ou inactive, on bloque l'acces
            $organization = $user->organization;
            if ($organization && $organization->status === \App\Enums\OrganizationStatus::SUSPENDED) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Votre organisation est suspendue. Contactez l\'administrateur.');
            }

            // 4. On s'assure que le context Spatie est fixe pour cet utilisateur
            // Les rôles sont globaux (organization_id = null), donc on ne filtre pas par team
            // Cela permet à ORG_ADMIN, MANAGER, MEMBER d'avoir leurs permissions partout
            setPermissionsTeamId(null);
        }

        return $next($request);
    }
}
