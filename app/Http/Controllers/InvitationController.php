<?php

namespace App\Http\Controllers;

use App\Actions\Invitation\AcceptInvitationAction;
use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    /**
     * Gere le clic sur le lien d'invitation.
     * 3 scenarios : user connecte sans org, user pas connecte (email connu), user inconnu.
     */
    public function __invoke(string $token)
    {
        $invitation = AcceptInvitationAction::findByToken($token);

        if (!$invitation) {
            return redirect()->route('login')
                ->with('error', 'Cette invitation est invalide ou a expiré.');
        }

        // Scenario 1 : User connecte sans org -> accepter directement
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->organization_id) {
                return redirect()->route('dashboard')
                    ->with('warning', 'Vous appartenez déjà à une organisation.');
            }

            (new AcceptInvitationAction)->execute($invitation, $user);

            return redirect()->route('dashboard')
                ->with('success', "Bienvenue dans l'espace {$invitation->organization?->name} !");
        }

        // Scenario 2/3 : User pas connecte -> stocker le token en session et rediriger
        session(['invitation_token' => $token]);

        // Si l'email correspond a un compte existant -> login
        $existingUser = \App\Models\User::withoutGlobalScopes()
            ->where('email', $invitation->email)
            ->first();

        if ($existingUser) {
            return redirect()->route('login')
                ->with('info', 'Connectez-vous pour rejoindre l\'organisation.');
        }

        // Sinon -> register avec email pre-rempli
        return redirect()->route('register', ['email' => $invitation->email])
            ->with('info', 'Créez votre compte pour rejoindre l\'organisation.');
    }
}
