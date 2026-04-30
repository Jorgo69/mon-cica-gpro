<?php

namespace App\Actions\Admin\Member;

use App\Models\User;
use App\Enums\AccountType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SaveMemberAction
{
    /**
     * Crée ou met à jour un membre au sein de l'organisation.
     *
     * @param array $data Les données validées
     * @param User|null $member Le membre à modifier (null pour création)
     * @return User
     */
    public function execute(array $data, ?User $member = null): User
    {
        return DB::transaction(function () use ($data, $member) {
            $currentUser = auth()->user();
            $isCreation = !$member;

            // Initialisation ou récupération
            $user = $member ?? new User();

            // Affectation des champs de base
            $user->name = $data['name'];
            $user->email = $data['email'];
            
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->telephone = $data['telephone'] ?? null;
            $user->sexe = $data['sexe'] ?? null;
            $user->numero_identification = $data['numero_identification'] ?? null;
            $user->pays = $data['pays'] ?? null;
            $user->ville = $data['ville'] ?? null;
            $user->department = $data['department'] ?? null;
            
            // Enum Role (AccountType) — avec verification des roles assignables
            $targetRole = $data['role'] instanceof AccountType ? $data['role'] : AccountType::from($data['role']);

            if ($currentUser && !in_array($targetRole, $currentUser->role->assignableRoles())) {
                throw new \InvalidArgumentException("Vous n'avez pas le droit d'assigner le rôle {$targetRole->label()}.");
            }

            $user->role = $targetRole;
            
            // multi-tenant : on force l'organisation si c'est une création
            if ($isCreation && $currentUser) {
                $orgId = $currentUser->organization_id;

                // ROOT en impersonation : utilise l'org cible
                if ($currentUser->role === AccountType::ROOT && session('acting_as_organization_id')) {
                    $orgId = session('acting_as_organization_id');
                }

                if ($orgId) {
                    $user->organization_id = $orgId;
                }
            }

            // Gestion de l'image
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                // Supprimer l'ancienne si elle existe
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $user->image = $data['image']->store('members', 'public');
            }

            $user->save();

            // --- Synchronisation Spatie Roles ---
            // Si un rôle Spatie est explicitement fourni, on l'utilise ; sinon on déduit depuis AccountType
            $roleName = $data['spatie_role'] ?? $this->mapAccountTypeToSpatieRole($user->role);

            // On s'assure d'être dans le contexte de l'organisation pour assigner le rôle
            if ($user->organization_id) {
                setPermissionsTeamId($user->organization_id);
            }

            $user->syncRoles([$roleName]);

            return $user;
        });
    }

    /**
     * Mappe l'AccountType vers le nom exact du rôle défini dans les seeders.
     */
    protected function mapAccountTypeToSpatieRole(AccountType $type): string
    {
        return match($type) {
            AccountType::ROOT => 'IT_ADMIN',
            AccountType::ORG_ADMIN => 'ORG_ADMIN',
            AccountType::ORG_USER => 'MEMBER',
            AccountType::INDEPENDENT => 'MEMBER',
            default => 'MEMBER',
        };
    }
}
