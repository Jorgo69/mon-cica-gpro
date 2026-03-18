<?php

namespace App\Livewire\Auth;

use App\Http\Requests\Auth\LoginRequest;
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

    /**
     * Authenticate the user.
     */
    public function login()
    {
        $this->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));
            $this->notifyToast('danger', trans('auth.failed'), 'Accès refusé');
            return;
        }

        session()->regenerate();

        $this->notifyToastSession('success', trans('auth.success'), 'Accès autorisé');
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function render()
    {
        return view('livewire.auth.login-livewire')
            ->layout('layouts.guest');
    }
}
