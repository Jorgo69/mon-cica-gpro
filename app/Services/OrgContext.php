<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

/**
 * Service central de contexte organisationnel.
 *
 * Singleton enregistre dans AppServiceProvider.
 * Utilise partout : Livewire, Controllers, Blade, Middleware, Traits.
 *
 * Usage :
 *   OrgContext::orgId()           // UUID org active (ou null)
 *   OrgContext::isRoot()          // system_admin ?
 *   OrgContext::isImpersonating() // ROOT dans une org ?
 *   OrgContext::mustFilter()      // doit-on filtrer par org ?
 *   OrgContext::orgIdOrFail()     // UUID ou abort(403)
 */
class OrgContext
{
    // ─── Identite ──────────────────────────────────────────

    /**
     * L'ID de l'org active :
     * - org_admin / org_user → user.organization_id
     * - independent → null
     * - ROOT impersonating → session acting_as_organization_id
     * - ROOT libre → null
     */
    public static function orgId(): ?string
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // ROOT en impersonation
        if ($user->role === AccountType::ROOT) {
            return session('acting_as_organization_id');
        }

        // Tous les autres : leur org
        return $user->organization_id;
    }

    /**
     * Comme orgId() mais abort 403 si null.
     * Pour les actions qui EXIGENT un contexte org.
     */
    public static function orgIdOrFail(): string
    {
        $orgId = static::orgId();

        if (!$orgId) {
            abort(403, 'Aucune organisation active.');
        }

        return $orgId;
    }

    /**
     * Nom de l'org active (pour affichage).
     */
    public static function orgName(): ?string
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        if ($user->role === AccountType::ROOT) {
            return session('acting_as_organization_name');
        }

        return $user->organization?->name;
    }

    // ─── Roles ─────────────────────────────────────────────

    public static function isRoot(): bool
    {
        return Auth::user()?->role === AccountType::ROOT;
    }

    public static function isOrgAdmin(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        // ROOT en impersonation a les droits admin
        if ($user->role === AccountType::ROOT && session('acting_as_organization_id')) {
            return true;
        }

        return $user->role === AccountType::ORG_ADMIN;
    }

    public static function isIndependent(): bool
    {
        return Auth::user()?->role === AccountType::INDEPENDENT;
    }

    // ─── Contexte ──────────────────────────────────────────

    /**
     * ROOT est-il en train de superviser une org ?
     */
    public static function isImpersonating(): bool
    {
        return static::isRoot() && session()->has('acting_as_organization_id');
    }

    /**
     * Doit-on filtrer les queries par organization_id ?
     * true pour tout le monde SAUF ROOT libre (sans impersonation).
     */
    public static function mustFilter(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        // ROOT libre → voit tout
        if ($user->role === AccountType::ROOT && !session('acting_as_organization_id')) {
            return false;
        }

        // Tous les autres (y compris ROOT en impersonation) → filtrer
        return true;
    }

    /**
     * ROOT peut-il bypass les restrictions ?
     * (Toujours trace via Spatie Activity Log)
     */
    public static function canBypass(): bool
    {
        return static::isRoot();
    }

    // ─── Helpers pour formulaires ──────────────────────────

    /**
     * Retourne les donnees d'org a injecter lors d'un create().
     * Usage : Model::create(array_merge($data, OrgContext::createData()))
     */
    public static function createData(): array
    {
        $orgId = static::orgId();

        if (!$orgId) {
            return [];
        }

        return ['organization_id' => $orgId];
    }

    /**
     * Retourne le user_id du createur.
     * Pour ROOT en impersonation, c'est quand meme le ROOT (tracabilite).
     */
    public static function creatorData(): array
    {
        $userId = Auth::id();

        if (!$userId) {
            return [];
        }

        return ['creator_user_id' => $userId];
    }

    /**
     * Combine createData + creatorData.
     * Usage : Model::create(array_merge($validated, OrgContext::contextData()))
     */
    public static function contextData(): array
    {
        return array_merge(static::createData(), static::creatorData());
    }
}
