<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GdprDeleteService
{
    /**
     * Anonymize and soft-delete a user account.
     *
     * @throws \InvalidArgumentException if user is sole owner of an org
     */
    public static function anonymizeAndDelete(User $user): void
    {
        // Block if user is the sole owner of an org
        if ($user->organization_id && $user->role === AccountType::ORG_ADMIN) {
            $org = $user->organization;
            if ($org && $org->isOwner($user)) {
                $otherAdmins = User::where('organization_id', $org->id)
                    ->where('id', '!=', $user->id)
                    ->where('role', AccountType::ORG_ADMIN)
                    ->count();

                if ($otherAdmins === 0) {
                    throw new \InvalidArgumentException(
                        __('settings.delete.must_transfer_ownership')
                    );
                }
            }
        }

        DB::transaction(function () use ($user) {
            $anonymousId = 'deleted-' . Str::random(8);

            // Anonymize user data (keep the record for audit trail)
            $user->update([
                'name' => __('settings.delete.deleted_user'),
                'email' => $anonymousId . '@deleted.local',
                'telephone' => null,
                'sexe' => null,
                'pays' => null,
                'ville' => null,
                'department' => null,
                'password' => bcrypt(Str::random(32)),
                'meta' => null,
                'remember_token' => null,
                'organization_id' => null,
                'role' => null,
            ]);

            // Delete related data
            $user->socialAccounts()->delete();
            $user->notifications()->delete();

            if (class_exists(\App\Models\FcmToken::class)) {
                \App\Models\FcmToken::where('user_id', $user->id)->delete();
            }

            // Delete comments
            \App\Models\Comment::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->delete();

            // Unassign from activities (don't delete activities)
            \App\Models\Activity::withoutGlobalScopes()
                ->where('responsible_user_id', $user->id)
                ->update(['responsible_user_id' => null]);

            // Remove AI config
            \App\Models\AiConfig::where('configurable_type', User::class)
                ->where('configurable_id', $user->id)
                ->delete();

            // Soft delete the user
            $user->delete();
        });
    }

    /**
     * Schedule organization deletion with 30-day grace period.
     * Marks org for deletion — actual deletion happens via scheduled command.
     */
    public static function scheduleOrgDeletion(Organization $org, User $requestedBy): void
    {
        if (!$org->isOwner($requestedBy)) {
            throw new \InvalidArgumentException(__('settings.delete.not_owner'));
        }

        $org->setMeta('deletion_scheduled_at', now()->toISOString());
        $org->setMeta('deletion_requested_by', $requestedBy->id);
        $org->save();

        // Notify all members
        $org->users->each(function ($member) use ($org) {
            $member->notify(new \App\Notifications\OrgDeletionScheduledNotification($org));
        });
    }

    /**
     * Cancel a scheduled organization deletion.
     */
    public static function cancelOrgDeletion(Organization $org): void
    {
        $org->forgetMeta('deletion_scheduled_at');
        $org->forgetMeta('deletion_requested_by');
        $org->save();
    }

    /**
     * Execute organization deletion (called by scheduled command after 30 days).
     */
    public static function executeOrgDeletion(Organization $org): void
    {
        DB::transaction(function () use ($org) {
            // Detach all members (they keep their accounts, just lose org)
            User::where('organization_id', $org->id)->update([
                'organization_id' => null,
                'role' => null,
            ]);

            // Soft-delete all projects
            $org->projects()->delete();

            // Delete org AI config
            \App\Models\AiConfig::where('configurable_type', Organization::class)
                ->where('configurable_id', $org->id)
                ->delete();

            // Delete invitations
            \App\Models\Invitation::where('organization_id', $org->id)->delete();

            // Soft-delete the org
            $org->delete();
        });
    }
}
