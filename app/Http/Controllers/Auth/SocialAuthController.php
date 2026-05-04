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

        // Store intent (login or register) in session for the callback
        $intent = request()->query('intent', 'login');
        session(['social_auth_intent' => in_array($intent, ['login', 'register']) ? $intent : 'login']);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider, SocialAuthService $service): RedirectResponse
    {
        if (!$this->isValidProvider($provider)) {
            abort(404);
        }

        $intent = session()->pull('social_auth_intent', 'login');

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route($intent === 'register' ? 'register' : 'login')
                ->with('error', __('auth.social_failed', ['provider' => ucfirst($provider)]));
        }

        if (!$socialUser->getEmail()) {
            return redirect()->route($intent === 'register' ? 'register' : 'login')
                ->with('error', __('auth.social_no_email', ['provider' => ucfirst($provider)]));
        }

        $result = $service->handleCallback($provider, $socialUser, $intent);

        // If login intent but no account exists → refuse, redirect to register
        if ($result === null) {
            return redirect()->route('register')
                ->with('error', __('auth.social_no_account'));
        }

        Auth::login($result, remember: true);
        session()->regenerate();

        // Auto-accept invitation si token en session
        $service->handleInvitation($result);

        // Nouveau user sans org → onboarding
        if (!$result->organization_id && !$result->is_independent) {
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
