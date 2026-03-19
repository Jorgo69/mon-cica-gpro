<?php

namespace App\Http\Middleware;

use App\Enums\AccountType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$types
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Si aucun type spécifié, on laisse passer (juste auth)
        if (empty($types)) {
            return $next($request);
        }

        // Vérification par rapport à l'Enum AccountType
        foreach ($types as $type) {
            // On vérifie si la valeur correspond à un des cases de l'Enum
            if ($user->role->value === $type) {
                return $next($request);
            }
        }

        abort(403, "Accès refusé : Type de compte non autorisé.");
    }
}
