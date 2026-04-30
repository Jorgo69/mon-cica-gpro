<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Actions\Invitation\AcceptInvitationAction;
use App\Livewire\Traits\WithToastNotifications;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Livewire\Component;

class RegisterLivewire extends Component
{
    use WithToastNotifications;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $hasInvitation = false;

    public function mount()
    {
        // Pre-remplir l'email si on vient d'un lien d'invitation
        if (request()->has('email')) {
            $this->email = request()->get('email');
        }
        $this->hasInvitation = session()->has('invitation_token');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(RegisterUserAction $registerAction)
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $registerAction->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        Auth::login($user);

        // Si un token d'invitation est en session, accepter directement
        $invitationToken = session()->pull('invitation_token');
        if ($invitationToken) {
            $invitation = AcceptInvitationAction::findByToken($invitationToken);
            if ($invitation) {
                (new AcceptInvitationAction)->execute($invitation, $user);
                $orgName = $invitation->organization?->name ?? 'l\'organisation';
                $this->notifyToastSession('success', "Compte créé et vous avez rejoint {$orgName} !", 'Bienvenue !');
                return redirect()->route('dashboard');
            }
        }

        $this->notifyToastSession('success', 'Votre compte a été créé avec succès.', 'Bienvenue !');

        return redirect()->route('onboarding');
    }

    public function render()
    {
        return view('livewire.auth.register-livewire')
            ->layout('layouts.guest');
    }
}
