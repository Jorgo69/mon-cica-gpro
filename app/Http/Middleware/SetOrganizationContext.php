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

            // 1. Les Admins IT ont accès à tout, pas besoin de contexte restrictif
            if ($user->role === \App\Enums\AccountType::ADMIN) {
                return $next($request);
            }

            // 2. Si l'organisation est suspendue ou inactive, on bloque l'accès
            $organization = $user->organization;
            if ($organization && $organization->status === \App\Enums\OrganizationStatus::SUSPENDED) {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Votre organisation est suspendue. Contactez l\'administrateur.');
            }

            // 3. (Optionnel) Ici on pourrait injecter l'ID de l'organisation dans la session 
            // pour des besoins de reporting globaux.
        }

        return $next($request);
    }
}
