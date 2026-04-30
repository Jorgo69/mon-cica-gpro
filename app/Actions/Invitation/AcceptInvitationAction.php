<?php

namespace App\Actions\Invitation;

use App\Enums\AccountType;
use App\Enums\InvitationStatus;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AcceptInvitationAction
{
    /**
     * Accepte une invitation (par token ou code).
     * Rattache l'utilisateur a l'organisation et assigne les roles.
     *
     * @param Invitation $invitation
     * @param User $user L'utilisateur qui accepte
     * @return User
     */
    public function execute(Invitation $invitation, User $user): User
    {
        if (!$invitation->isValid()) {
            throw new \RuntimeException('Cette invitation n\'est plus valide.');
        }

        return DB::transaction(function () use ($invitation, $user) {
            // Rattacher l'utilisateur a l'organisation
            $user->organization_id = $invitation->organization_id;
            $user->role = AccountType::from($invitation->role);
            $user->is_independent = false;
            $user->save();

            // Assigner le role Spatie dans le contexte de l'org
            setPermissionsTeamId($invitation->organization_id);
            $user->syncRoles([$invitation->spatie_role]);

            // Marquer l'invitation comme acceptee
            $invitation->update([
                'status' => InvitationStatus::ACCEPTED,
                'accepted_at' => now(),
            ]);

            return $user;
        });
    }

    /**
     * Trouve une invitation valide par token.
     */
    public static function findByToken(string $token): ?Invitation
    {
        return Invitation::withoutGlobalScopes()
            ->where('token', $token)
            ->valid()
            ->first();
    }

    /**
     * Trouve une invitation valide par code.
     */
    public static function findByCode(string $code): ?Invitation
    {
        return Invitation::withoutGlobalScopes()
            ->where('code', strtoupper(trim($code)))
            ->valid()
            ->first();
    }
}
