<?php

namespace App\Observers;

use App\Models\Skill;
use Illuminate\Support\Facades\Cache;

class SkillObserver
{
    /**
     * 
     */
    public $afterCommit = true;

    /**
     * Handle the Skill "created" event.
     *
     * @param  \App\Models\Skill  $skill
     * @return void
     */
    public function created(Skill $skill)
    {
        Cache::tags(['search-user'])->flush();
    }

    /**
     * Handle the Skill "updated" event.
     *
     * @param  \App\Models\Skill  $skill
     * @return void
     */
    public function updated(Skill $skill)
    {
        //
    }

    /**
     * Handle the Skill "deleted" event.
     *
     * @param  \App\Models\Skill  $skill
     * @return void
     */
    public function deleted(Skill $skill)
    {
        
    }

    /**
     * Handle the Skill "restored" event.
     *
     * @param  \App\Models\Skill  $skill
     * @return void
     */
    public function restored(Skill $skill)
    {
        //
    }

    /**
     * Handle the Skill "force deleted" event.
     *
     * @param  \App\Models\Skill  $skill
     * @return void
     */
    public function forceDeleted(Skill $skill)
    {
        //
    }
}
