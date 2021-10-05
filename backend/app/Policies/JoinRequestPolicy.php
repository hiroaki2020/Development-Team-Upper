<?php

namespace App\Policies;

use App\Models\JoinRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JoinRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can cancel the join request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\JoinRequest  $joinRequest
     * @return mixed
     */
    public function cancelJoinRequest(User $user, JoinRequest $joinRequest)
    {
        return (int) $user->id === (int) $joinRequest->user_id;
    }
}
