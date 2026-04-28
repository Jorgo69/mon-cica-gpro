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

        $role = auth()->user()->role;

        // ROOT bypass : accede a toutes les routes protegees
        if ($role === \App\Enums\AccountType::ROOT) {
            return $next($request);
        }

        if ($role) {
            foreach ($types as $type) {
                if ($role->value === $type) {
                    return $next($request);
                }
            }
        }

        return redirect()->route('dashboard');
    }
}
