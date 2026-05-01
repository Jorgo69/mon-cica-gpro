<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GdprDeleteService
{
    public static function anonymizeAndDelete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $anonymousId = 'deleted-' . Str::random(8);

            // Anonymize user data (keep the record for audit trail)
            $user->update([
                'name' => 'Utilisateur supprime',
                'email' => $anonymousId . '@deleted.local',
                'telephone' => null,
                'sexe' => null,
                'pays' => null,
                'ville' => null,
                'department' => null,
                'password' => bcrypt(Str::random(32)),
                'meta' => null,
                'remember_token' => null,
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

            // Soft delete the user
            $user->delete();
        });
    }
}
