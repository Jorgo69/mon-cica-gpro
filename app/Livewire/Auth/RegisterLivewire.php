<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RegisterUserAction;
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

        $this->notifyToastSession('success', 'Votre compte a été créé avec succès.', 'Bienvenue !');

        return redirect()->route('onboarding');
    }

    public function render()
    {
        return view('livewire.auth.register-livewire')
            ->layout('layouts.guest');
    }
}
