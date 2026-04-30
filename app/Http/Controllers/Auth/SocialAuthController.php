<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private const PROVIDERS = ['google', 'facebook', 'microsoft'];

    public function redirect(string $provider): RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
    {
        if (!$this->isValidProvider($provider)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider, SocialAuthService $service): RedirectResponse
    {
        if (!$this->isValidProvider($provider)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Echec de l\'authentification avec ' . ucfirst($provider) . '. Veuillez reessayer.');
        }

        if (!$socialUser->getEmail()) {
            return redirect()->route('login')
                ->with('error', 'Impossible de recuperer votre adresse email depuis ' . ucfirst($provider) . '.');
        }

        $user = $service->handleCallback($provider, $socialUser);

        Auth::login($user, remember: true);
        session()->regenerate();

        // Auto-accept invitation si token en session
        $service->handleInvitation($user);

        // Nouveau user sans org → onboarding
        if (!$user->organization_id && !$user->is_independent) {
            return redirect()->route('onboarding');
        }

        return redirect()->route('dashboard');
    }

    private function isValidProvider(string $provider): bool
    {
        return in_array($provider, self::PROVIDERS)
            && config("services.{$provider}.client_id");
    }
}
