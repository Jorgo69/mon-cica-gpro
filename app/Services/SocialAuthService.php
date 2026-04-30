<?php

namespace App\Services;

use App\Actions\Invitation\AcceptInvitationAction;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialAuthService
{
    public function handleCallback(string $provider, SocialiteUser $socialUser): User
    {
        return DB::transaction(function () use ($provider, $socialUser) {
            // 1. Cherche un social account existant
            $socialAccount = SocialAccount::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if ($socialAccount) {
                $this->updateTokens($socialAccount, $socialUser);
                return $socialAccount->user;
            }

            // 2. Cherche un user existant par email
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                $this->createSocialAccount($user, $provider, $socialUser);
                return $user;
            }

            // 3. Creer un nouveau user (sans password)
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Utilisateur',
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
            ]);

            $this->createSocialAccount($user, $provider, $socialUser);

            return $user;
        });
    }

    public function handleInvitation(User $user): bool
    {
        $token = session('invitation_token');
        if (!$token || $user->organization_id) {
            return false;
        }

        $invitation = AcceptInvitationAction::findByToken($token);
        if (!$invitation) {
            return false;
        }

        (new AcceptInvitationAction())->execute($invitation, $user);
        session()->forget('invitation_token');

        return true;
    }

    public function unlinkProvider(User $user, string $provider): bool
    {
        return $user->socialAccounts()
            ->where('provider', $provider)
            ->delete() > 0;
    }

    private function createSocialAccount(User $user, string $provider, SocialiteUser $socialUser): SocialAccount
    {
        return SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'provider_email' => $socialUser->getEmail(),
            'avatar_url' => $socialUser->getAvatar(),
            'access_token' => $socialUser->token ?? null,
            'refresh_token' => $socialUser->refreshToken ?? null,
        ]);
    }

    private function updateTokens(SocialAccount $account, SocialiteUser $socialUser): void
    {
        $account->update([
            'access_token' => $socialUser->token ?? $account->access_token,
            'refresh_token' => $socialUser->refreshToken ?? $account->refresh_token,
            'avatar_url' => $socialUser->getAvatar() ?? $account->avatar_url,
        ]);
    }
}
