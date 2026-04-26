<?php

namespace App\Actions\Invitation;

use App\Enums\AccountType;
use App\Enums\InvitationStatus;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\InvitationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SendInvitationAction
{
    /**
     * Envoie une invitation a rejoindre une organisation.
     *
     * @param array $data [email, organization_id?, role, spatie_role]
     * @return Invitation
     */
    public function execute(array $data): Invitation
    {
        $sender = auth()->user();
        $orgId = $data['organization_id'] ?? $sender->organization_id;

        // ROOT doit specifier l'org cible
        if ($sender->role === AccountType::ROOT && !$orgId) {
            throw ValidationException::withMessages([
                'organization_id' => 'Une organisation cible est requise.',
            ]);
        }

        // Verifier que l'email n'est pas deja un membre actif de l'org
        $existingMember = User::withoutGlobalScopes()
            ->where('email', $data['email'])
            ->where('organization_id', $orgId)
            ->first();

        if ($existingMember) {
            throw ValidationException::withMessages([
                'email' => 'Cet utilisateur est déjà membre de cette organisation.',
            ]);
        }

        // Verifier qu'il n'y a pas deja une invitation pending
        $existingInvitation = Invitation::withoutGlobalScopes()
            ->where('email', $data['email'])
            ->where('organization_id', $orgId)
            ->where('status', InvitationStatus::PENDING)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            throw ValidationException::withMessages([
                'email' => 'Une invitation est déjà en attente pour cet email.',
            ]);
        }

        $invitation = Invitation::create([
            'email' => $data['email'],
            'token' => Str::random(64),
            'code' => strtoupper(Str::random(6)),
            'organization_id' => $orgId,
            'invited_by' => $sender->id,
            'role' => $data['role'] ?? 'org_user',
            'spatie_role' => $data['spatie_role'] ?? 'MEMBER',
            'status' => InvitationStatus::PENDING,
            'expires_at' => now()->addDays(7),
        ]);

        Notification::route('mail', $data['email'])
            ->notify(new InvitationNotification($invitation));

        return $invitation;
    }
}
