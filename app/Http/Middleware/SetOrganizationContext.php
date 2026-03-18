<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetOrganizationContext
{
    /**
     * Handle an incoming request.
     * 
     * Ce middleware garantit que l'utilisateur accède au bon contexte organisationnel.
     * Pour l'instant, il vérifie simplement que l'utilisateur a une organisation active (sauf admin).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // 1. Les Admins IT ont accès à tout, pas besoin de contexte restrictif (bypass global)
            // Note: On utilise le nom constant de l'Enum si possible
            if ($user->role->value === \App\Enums\AccountType::ADMIN->value) {
                return $next($request);
            }

            // 2. Gestion de l'Onboarding (Si aucune organisation rattachée et non indépendant)
            if (!$user->organization_id && !$user->is_independent) {
                $onboardingRoutes = ['onboarding', 'logout'];

                // Autoriser les requêtes Livewire (AJAX) depuis la page d'onboarding
                // Sans cette exception, le middleware redirige /livewire/update en 302
                // et Livewire crash car il reçoit du HTML au lieu du JSON attendu.
                $isLivewireRequest = $request->hasHeader('X-Livewire');

                if (!$request->routeIs($onboardingRoutes) && !$isLivewireRequest) {
                    return redirect()->route('onboarding');
                }
                return $next($request);
            }

            // 3. Si l'organisation est suspendue ou inactive, on bloque l'accès
            $organization = $user->organization;
            if ($organization && $organization->status === \App\Enums\OrganizationStatus::SUSPENDED) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Votre organisation est suspendue. Contactez l\'administrateur.');
            }

            // 4. On s'assure que le context Spatie est fixé pour cet utilisateur
            setPermissionsTeamId($user->organization_id);
        }

        return $next($request);
    }
}
