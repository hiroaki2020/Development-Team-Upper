<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Skill;
use App\Observers\SkillObserver;
use App\Models\Team;
use App\Observers\TeamObserver;
use App\Models\Requirement;
use App\Observers\RequirementObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Skill::observe(SkillObserver::class);
        Team::observe(TeamObserver::class);
        Requirement::observe(RequirementObserver::class);
    }
}
