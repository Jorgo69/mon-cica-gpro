<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPlanFeature
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // ROOT bypasses all plan checks
        if ($user->role === \App\Enums\AccountType::ROOT) {
            return $next($request);
        }

        // Independents have no org plan, treat as free
        if ($user->role === \App\Enums\AccountType::INDEPENDENT) {
            return $next($request);
        }

        $org = $user->organization;

        if (! $org) {
            return $next($request);
        }

        // Check if plan is expired
        if (! $org->isPlanActive()) {
            return redirect()->route('dashboard')
                ->with('warning', __('plans.expired'));
        }

        // Check feature access
        if (! $org->hasFeature($feature)) {
            return redirect()->back()
                ->with('warning', __('plans.feature_locked', ['feature' => $feature]));
        }

        return $next($request);
    }
}
