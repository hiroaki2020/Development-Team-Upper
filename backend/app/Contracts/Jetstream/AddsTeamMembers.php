<?php

namespace App\Contracts\Jetstream;

interface AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  int  $id
     * @return void
     */
    public function add($user, $team, int $id, string $role = null);
}
