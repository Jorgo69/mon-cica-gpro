<?php

namespace App\Services\AI;

use App\Enums\AccountType;
use App\Enums\AiProvider;
use App\Models\AiConfig;
use App\Models\Organization;
use App\Models\User;
use App\Services\OrgContext;

/**
 * Resolves the active AI configuration for the current user.
 *
 * Priority:
 *   1. User's own config (INDEPENDENT only)
 *   2. Organization config (if org has its own key)
 *   3. Global config from .env (fallback)
 *   4. Disabled (no key anywhere)
 *
 * Also checks if admin has disabled AI for a specific member.
 */
class AiConfigResolver
{
    /**
     * Resolve the active AI config for a given user.
     * Returns an array with keys: provider, api_key, base_url, model, source.
     * Returns null if AI is disabled/unavailable.
     */
    public static function resolve(?User $user = null): ?array
    {
        $user = $user ?? auth()->user();
        if (!$user) {
            // No authenticated user — fallback to global (for Blade views during rendering)
            return self::resolveGlobal();
        }

        // Check if admin has disabled AI for this member
        if (self::isDisabledForUser($user)) {
            return null;
        }

        // 1. INDEPENDENT: check own config
        if ($user->role === AccountType::INDEPENDENT) {
            $config = $user->aiConfig;
            if ($config && $config->isUsable()) {
                return self::configToArray($config, 'user');
            }
            // Fallback to global
            return self::resolveGlobal();
        }

        // 2. ROOT: always use global
        if ($user->role === AccountType::ROOT) {
            return self::resolveGlobal();
        }

        // 3. Org members (ORG_ADMIN, ORG_USER): check org config first
        if ($user->organization_id) {
            $orgConfig = AiConfig::where('configurable_type', Organization::class)
                ->where('configurable_id', $user->organization_id)
                ->first();

            if ($orgConfig) {
                // Org has explicitly disabled AI
                if (!$orgConfig->enabled) {
                    return null;
                }
                // Org has its own usable config
                if ($orgConfig->isUsable()) {
                    return self::configToArray($orgConfig, 'organization');
                }
            }

            // Fallback to global
            return self::resolveGlobal();
        }

        return self::resolveGlobal();
    }

    /**
     * Check if AI is available for the current user (fast check, no full resolve).
     */
    public static function isAvailable(?User $user = null): bool
    {
        return self::resolve($user) !== null;
    }

    /**
     * Resolve global config: DB (ROOT config) first, then .env fallback.
     */
    public static function resolveGlobal(): ?array
    {
        // 1. Check DB global config (set by ROOT in /system/ai-config)
        $dbConfig = AiConfig::where('configurable_type', 'global')
            ->where('configurable_id', 'system')
            ->first();

        if ($dbConfig) {
            if (!$dbConfig->enabled) {
                return null; // ROOT has globally disabled AI
            }
            if ($dbConfig->isUsable()) {
                return self::configToArray($dbConfig, 'global');
            }
        }

        // 2. Fallback to .env keys
        $groqKey = config('gpro.ai.groq_api_key');
        if (!empty($groqKey)) {
            return [
                'provider' => AiProvider::GROQ,
                'api_key' => $groqKey,
                'base_url' => AiProvider::GROQ->baseUrl(),
                'model' => config('gpro.ai.groq_model', AiProvider::GROQ->defaultModel()),
                'source' => 'env',
            ];
        }

        $geminiKey = config('gpro.ai.gemini_api_key');
        if (!empty($geminiKey)) {
            return [
                'provider' => AiProvider::GEMINI,
                'api_key' => $geminiKey,
                'base_url' => AiProvider::GEMINI->baseUrl(),
                'model' => config('gpro.ai.gemini_model', AiProvider::GEMINI->defaultModel()),
                'source' => 'env',
            ];
        }

        return null;
    }

    /**
     * Check if AI has been disabled for this user by their org admin.
     */
    public static function isDisabledForUser(User $user): bool
    {
        return (bool) ($user->getMeta('ai.disabled_by_admin') ?? false);
    }

    /**
     * Convert an AiConfig model to a standardized array.
     */
    protected static function configToArray(AiConfig $config, string $source): array
    {
        return [
            'provider' => $config->provider,
            'api_key' => $config->api_key,
            'base_url' => $config->getEffectiveBaseUrl(),
            'model' => $config->getEffectiveModel(),
            'source' => $source,
        ];
    }
}
