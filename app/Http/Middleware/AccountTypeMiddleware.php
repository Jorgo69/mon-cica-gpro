<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountTypeMiddleware
{
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (empty($types)) {
            return $next($request);
        }

        $user = auth()->user();

        foreach ($types as $type) {
            if ($user->account_type->value === $type) {
                return $next($request);
            }
        }

        abort(403, 'Accès refusé : Type de compte non autorisé.');
    }
}
