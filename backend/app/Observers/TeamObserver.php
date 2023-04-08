<?php

namespace App\Observers;

use App\Models\Team;
use Illuminate\Support\Facades\Cache;

class TeamObserver
{
    /**
     * 
     */
    public $afterCommit = true;

    /**
     * Handle the Team "created" event.
     *
     * @param  \App\Models\Team  $team
     * @return void
     */
    public function created(Team $team)
    {
        Cache::tags(['search-team'])->flush();
    }

    /**
     * Handle the Team "updated" event.
     *
     * @param  \App\Models\Team  $team
     * @return void
     */
    public function updated(Team $team)
    {
        Cache::tags(['search-team'])->flush();
    }

    /**
     * Handle the Team "deleted" event.
     *
     * @param  \App\Models\Team  $team
     * @return void
     */
    public function deleted(Team $team)
    {
        Cache::tags(['search-team'])->flush();
    }

    /**
     * Handle the Team "restored" event.
     *
     * @param  \App\Models\Team  $team
     * @return void
     */
    public function restored(Team $team)
    {
        //
    }

    /**
     * Handle the Team "force deleted" event.
     *
     * @param  \App\Models\Team  $team
     * @return void
     */
    public function forceDeleted(Team $team)
    {
        //
    }
}
