<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkillOption;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Models\Team;

class SetTeamProfileController extends Controller
{
    public function index()
    {
        $team = '';
        $skillOptions = SkillOption::orderBy('skill', 'asc')->get();
        $currentRequirements = '';
        $currentOptions = '';
        $wanted = false;
        if(auth()->user()->hasTeams()) {
            $current_team_id = auth()->user()->current_team_id;
            $team = Team::findOrFail($current_team_id)->load('owner', 'users', 'teamInvitations', 'teamInvitations.user', 'joinRequests', 'joinRequests.user');
            $currentRequirements = $team->requirements()->where('required', true)->pluck('requirement')->all();
            $currentOptions = $team->requirements()->where('required', false)->pluck('requirement')->all();
            if($team->wanted) {
                $wanted = true;
            }
        }


        return Inertia::render('TeamProfile/SetTeamProfile', [
            'skillOptions' => $skillOptions,
            'team' => $team,
            'currentRequirements' => $currentRequirements,
            'currentOptions' => $currentOptions,
            'wantedPassed' => $wanted,
            'permissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', $team),
                'canDeleteTeam' => Gate::check('delete', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
                'canUpdateTeam' => Gate::check('update', $team),
            ],
        ]);
    }
}
