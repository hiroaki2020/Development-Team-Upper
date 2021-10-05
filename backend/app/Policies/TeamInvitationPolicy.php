<?php

namespace App\Policies;

use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamInvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can handle the team invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TeamInvitation  $teamInvitation
     * @return mixed
     */
    public function handleTeamInvitation(User $user, TeamInvitation $teamInvitation)
    {
        return (int) $user->id === (int) $teamInvitation->user_id;
    }
}
