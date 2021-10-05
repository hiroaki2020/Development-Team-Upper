<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use App\Contracts\Jetstream\AddsTeamMembers;
use App\Contracts\Jetstream\InvitesTeamMembers;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Jetstream::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);

        app()->singleton(AddsTeamMembers::class, AddTeamMember::class);
        app()->singleton(InvitesTeamMembers::class, InviteTeamMember::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('viewer', 'Viewer', [
            'read'
        ])->description('Viewer can join team chat.');

        Jetstream::role('editor', 'Editor', [
            'read',
            'create',
            'update',
        ])->description('On top of viewer\'s rights, editor can also edit team profile.');

        Jetstream::role('administrator', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('On top of editor\'s rights, administrator can invite/add/remove team members and can change members\' roles.');
    }
}
