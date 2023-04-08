<?php

namespace App\Observers;

use App\Models\Requirement;
use Illuminate\Support\Facades\Cache;

class RequirementObserver
{
    /**
     * 
     */
    public $afterCommit = true;

    /**
     * Handle the Requirement "created" event.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return void
     */
    public function created(Requirement $requirement)
    {
        Cache::tags(['search-team'])->flush();
    }

    /**
     * Handle the Requirement "updated" event.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return void
     */
    public function updated(Requirement $requirement)
    {
        //
    }

    /**
     * Handle the Requirement "deleted" event.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return void
     */
    public function deleted(Requirement $requirement)
    {
        //
    }

    /**
     * Handle the Requirement "restored" event.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return void
     */
    public function restored(Requirement $requirement)
    {
        //
    }

    /**
     * Handle the Requirement "force deleted" event.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return void
     */
    public function forceDeleted(Requirement $requirement)
    {
        //
    }
}
