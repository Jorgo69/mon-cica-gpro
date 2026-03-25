<?php

namespace App\Livewire\Auth;

use App\Livewire\Traits\WithToastNotifications;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
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
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));
            $this->notifyToast('danger', trans('auth.failed'), 'Accès refusé');
            return;
        }

        session()->regenerate();

        // Initialiser l'organisation active en session pour les org_members
        $user = Auth::user();
        $firstOrg = $user->organizations()->wherePivot('status', 'active')->first();
        if ($firstOrg) {
            session(['current_organization_id' => $firstOrg->id]);
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
