<?php

namespace App\Contracts\Jetstream;

interface InvitesTeamMembers
{
    /**
     * Invite a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  int  $id
     * @return void
     */
    public function invite($user, $team, int $id, string $role = null, string $message = null);
}
