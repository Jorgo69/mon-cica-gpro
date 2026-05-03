<?php

namespace App\Http\Middleware;

use App\Services\AI\AiConfigResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAiAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!AiConfigResolver::isAvailable()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('ai.config.ai_not_available'),
                ], 403);
            }

            abort(403, __('ai.config.ai_not_available'));
        }

        return $next($request);
    }
}
