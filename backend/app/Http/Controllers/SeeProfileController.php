<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Models\Team;
use Laravel\Jetstream\Jetstream;

class SeeProfileController extends Controller
{
    /**
     * Show user profile.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id)->load('skills');
        $team = '';
        $requested = '';
        $inviting = '';
        $member_or_not = false;
        if(auth()->check() && auth()->user()->current_team_id !== null) {
            $current_team_id = auth()->user()->current_team_id;
            $team = Team::findOrFail($current_team_id)->load('owner', 'users', 'teamInvitations', 'teamInvitations.user', 'joinRequests', 'joinRequests.user');
            $requested = $user->joinRequests()->where('team_id', $current_team_id)->first();
            $inviting = $user->teamInvitations()->where('team_id', $current_team_id)->first();
            $member_or_not = $team->hasUserWithId($id);
        }
        
        return Inertia::render('Profile/Profile', [
            'profile' => $user,
            'team' => $team,
            'requested' => $requested,
            'inviting' => $inviting,
            'member_or_not' => $member_or_not,
            'availableRoles' => array_values(Jetstream::$roles),
            'permissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
            ],
        ]);
    }
}
