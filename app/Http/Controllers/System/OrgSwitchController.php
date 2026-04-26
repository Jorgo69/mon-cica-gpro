<?php

namespace App\Http\Controllers\System;

use App\Enums\AccountType;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Spatie\Activitylog\Facades\LogBatch;

class OrgSwitchController extends Controller
{
    /**
     * ROOT entre dans le contexte d'une organisation.
     */
    public function enter(Request $request, string $organizationId)
    {
        $user = $request->user();

        if ($user->role !== AccountType::ROOT) {
            abort(403);
        }

        $organization = Organization::findOrFail($organizationId);

        // Stocker en session
        session([
            'acting_as_organization_id' => $organization->id,
            'acting_as_organization_name' => $organization->name,
        ]);

        // Configurer le contexte Spatie pour cette org
        setPermissionsTeamId($organization->id);

        // Logger l'accès (visible par l'org_admin dans ses logs)
        activity('root_access')
            ->causedBy($user)
            ->performedOn($organization)
            ->withProperties([
                'organization_id' => $organization->id,
                'organization_name' => $organization->name,
                'action' => 'enter',
            ])
            ->log("Root Admin a accédé à l'espace {$organization->name}");

        return redirect()->route('dashboard')
            ->with('success', "Vous êtes maintenant dans l'espace {$organization->name}");
    }

    /**
     * ROOT quitte le contexte d'une organisation.
     */
    public function leave(Request $request)
    {
        $user = $request->user();

        if ($user->role !== AccountType::ROOT) {
            abort(403);
        }

        $orgName = session('acting_as_organization_name', 'inconnue');

        // Logger la sortie
        activity('root_access')
            ->causedBy($user)
            ->withProperties([
                'action' => 'leave',
                'organization_name' => $orgName,
            ])
            ->log("Root Admin a quitté l'espace {$orgName}");

        // Nettoyer la session
        session()->forget(['acting_as_organization_id', 'acting_as_organization_name']);

        // Remettre le contexte Spatie à null (ROOT)
        setPermissionsTeamId(null);

        return redirect()->route('system.dashboard');
    }
}
