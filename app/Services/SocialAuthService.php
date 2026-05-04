<?php

namespace App\Services;

use App\Actions\Invitation\AcceptInvitationAction;
use App\Enums\AccountType;
use App\Enums\OrganizationStatus;
use App\Enums\PermissionLevel;
use App\Models\Organization;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialAuthService
{
    /**
     * @param string $intent 'login' or 'register'
     * @return User|null null if login intent but no account exists
     */
    public function handleCallback(string $provider, SocialiteUser $socialUser, string $intent = 'login'): ?User
    {
        return DB::transaction(function () use ($provider, $socialUser, $intent) {
            // 1. Cherche un social account existant → login OK (both intents)
            $socialAccount = SocialAccount::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if ($socialAccount) {
                $this->updateTokens($socialAccount, $socialUser);
                return $socialAccount->user;
            }

            // 2. Cherche un user existant par email → lie le social account + login
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                $this->createSocialAccount($user, $provider, $socialUser);
                return $user;
            }

            // 3. Aucun compte existant
            // Si intent = login → refuser (pas de creation de compte depuis la page login)
            if ($intent === 'login') {
                return null;
            }

            // Intent = register → creer le compte
            if (isSelfHosted() && DB::table('users')->lockForUpdate()->count() === 0) {
                $user = $this->createFirstAdminSocial($socialUser);
            } else {
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Utilisateur',
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(),
                ]);
            }

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

    private function createFirstAdminSocial(SocialiteUser $socialUser): User
    {
        Artisan::call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);

        $orgName = config('app.name', 'Mon Organisation');
        $org = Organization::create([
            'name' => $orgName,
            'slug' => Str::slug($orgName),
            'status' => OrganizationStatus::ACTIVE,
        ]);

        $user = User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Administrateur',
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(32)),
            'role' => AccountType::ORG_ADMIN,
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);

        $org->update(['owner_user_id' => $user->id]);

        setPermissionsTeamId(null);
        $level = PermissionLevel::ADMIN;
        $user->assignRole($level->spatieRole());
        $user->syncPermissions($level->permissions());

        return $user;
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
