<?php

namespace App\Http\Middleware;

use App\Enums\AccountType;
use App\Enums\OrganizationStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetOrganizationContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // 1. SYSTEM_ADMIN → bypass total, context Spatie global
        if ($user->account_type === AccountType::SYSTEM_ADMIN) {
            setPermissionsTeamId(null);
            return $next($request);
        }

        // 2. INDEPENDENT → pas d'org nécessaire, accès à /workspace
        if ($user->account_type === AccountType::INDEPENDENT) {
            return $next($request);
        }

        // 3. ORG_ADMIN / ORG_MEMBER → vérifier qu'il a au moins une organisation
        $hasOrg = $user->organizations()->wherePivot('status', 'active')->exists();

        if (!$hasOrg) {
            // Pas encore rattaché → onboarding
            $allowedRoutes = ['onboarding', 'logout'];
            $isLivewireRequest = $request->hasHeader('X-Livewire');

            if (!$request->routeIs($allowedRoutes) && !$isLivewireRequest) {
                return redirect()->route('onboarding');
            }
            return $next($request);
        }

        // 4. Résoudre l'organisation active en session
        $orgId = session('current_organization_id');

        if (!$orgId) {
            // Pas d'org en session → prendre la première org active
            $firstOrg = $user->organizations()->wherePivot('status', 'active')->first();
            $orgId = $firstOrg?->id;
            session(['current_organization_id' => $orgId]);
        }

        // 5. Vérifier le statut de l'org active
        if ($orgId) {
            $org = \App\Models\Organization::find($orgId);

            if ($org && $org->status === OrganizationStatus::SUSPENDED) {
                session()->forget('current_organization_id');
                auth()->logout();
                return redirect()->route('login')
                    ->with('error', 'Votre organisation est suspendue. Contactez l\'administrateur.');
            }
        }

        // 6. Contexte Spatie Permissions
        setPermissionsTeamId($orgId);

        return $next($request);
    }
}
