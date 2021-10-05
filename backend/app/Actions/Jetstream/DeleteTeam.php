<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesTeams;

class DeleteTeam implements DeletesTeams
{
    /**
     * Delete the given team.
     *
     * @param  mixed  $team
     * @return void
     */
    public function delete($team)
    {
        if(!is_null($team->conversation)) {
            $team->conversation->load('users')->delete();
        }
        
        $team->purge();
    }
}
