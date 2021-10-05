<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ValidateTeamDeletion
{
    /**
     * Validate that the team can be deleted by the given user.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @return void
     */
    public function validate($user, $team)
    {
        Gate::forUser($user)->authorize('delete', $team);
    }
}
