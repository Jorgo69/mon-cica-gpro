<?php

namespace App\Actions\Admin\Member;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteMemberAction
{
    /**
     * Supprime un membre et ses ressources associées (image).
     *
     * @param User $member
     * @return bool
     */
    public function execute(User $member): bool
    {
        return DB::transaction(function () use ($member) {
            // Supprimer l'image de profil si elle existe
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }

            // Suppression du membre (Soft delete est actif sur le modèle User)
            return $member->delete();
        });
    }
}
