<?php

namespace App\Livewire\Auth;

use App\Actions\Invitation\AcceptInvitationAction;
use App\Livewire\Traits\WithToastNotifications;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class LoginLivewire extends Component
{
    use WithToastNotifications;

    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Rate limiting: 5 attempts per minute per email+IP
        $throttleKey = Str::transliterate(Str::lower($this->email) . '|' . request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', trans('auth.throttle', ['seconds' => $seconds]));
            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($throttleKey, 60);
            $this->addError('email', trans('auth.failed'));
            $this->notifyToast('danger', trans('auth.failed'), 'Accès refusé');
            return;
        }

        RateLimiter::clear($throttleKey);
        session()->regenerate();

        // Si un token d'invitation est en session, accepter apres login
        $invitationToken = session()->pull('invitation_token');
        if ($invitationToken) {
            $invitation = AcceptInvitationAction::findByToken($invitationToken);
            if ($invitation && !Auth::user()->organization_id) {
                (new AcceptInvitationAction)->execute($invitation, Auth::user());
                $orgName = $invitation->organization?->name ?? 'l\'organisation';
                $this->notifyToastSession('success', "Vous avez rejoint {$orgName} !", 'Bienvenue');
                return redirect()->route('dashboard');
            }
        }

        $this->notifyToastSession('success', trans('auth.success'), 'Accès autorisé');
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function render()
    {
        return view('livewire.auth.login-livewire')
            ->layout('layouts.guest');
    }
}
