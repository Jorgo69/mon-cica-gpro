<?php

namespace App\Actions\Admin\Member;

use App\Models\User;
use App\Enums\AccountType;
use App\Enums\OrgMemberRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SaveMemberAction
{
    public function execute(array $data, ?User $member = null): User
    {
        return DB::transaction(function () use ($data, $member) {
            $orgId = session('current_organization_id');
            $isCreation = !$member;

            $user = $member ?? new User();

            $user->name                  = $data['name'];
            $user->email                 = $data['email'];
            // account_type reflète le niveau : org_admin si rôle pivot = org_admin, sinon org_member
            $pivotRole = $data['org_role'] ?? OrgMemberRole::MEMBER->value;
            $user->account_type = ($pivotRole === OrgMemberRole::ORG_ADMIN->value)
                ? AccountType::ORG_ADMIN
                : AccountType::ORG_MEMBER;
            $user->country               = $data['country'] ?? null;
            $user->telephone             = $data['telephone'] ?? null;
            $user->numero_identification = $data['numero_identification'] ?? null;

            // Location JSON depuis les champs individuels
            $user->location = array_filter([
                'ville'    => $data['ville'] ?? null,
                'quartier' => $data['quartier'] ?? null,
            ]);

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            // Image
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $user->image = $data['image']->store('members', 'public');
            }

            $user->save();

            // Pivot : lier/mettre à jour le rôle dans l'org
            if ($orgId) {
                $pivotRole = $data['org_role'] ?? OrgMemberRole::MEMBER->value;
                $pivotData = [
                    'role'   => $pivotRole,
                    'status' => 'active',
                ];

                if ($isCreation) {
                    $pivotData['joined_at'] = now();
                    $user->organizations()->attach($orgId, $pivotData);
                } else {
                    $user->organizations()->updateExistingPivot($orgId, $pivotData);
                }

                // Spatie Roles
                $roleName = $data['spatie_role'] ?? 'MEMBER';
                setPermissionsTeamId($orgId);
                $user->syncRoles([$roleName]);
            }

            return $user;
        });
    }
}
