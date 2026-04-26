<?php

namespace App\Actions\Invitation;

use App\Enums\InvitationStatus;
use App\Models\Invitation;

class RevokeInvitationAction
{
    public function execute(Invitation $invitation): Invitation
    {
        $invitation->update(['status' => InvitationStatus::REVOKED]);

        return $invitation;
    }
}
