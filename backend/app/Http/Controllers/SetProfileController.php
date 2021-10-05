<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkillOption;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Models\Team;

class SetProfileController extends Controller
{
    public function index()
    {
        $skillOptions = SkillOption::orderBy('skill', 'asc')->get();
        $team = '';
        $ownedSkills = auth()->user()->skills()->pluck('skill')->all();
        $currentRequirements = [];
        $currentOptions = [];
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
        return Inertia::render('Profile/SetProfile', [
            'skillOptions' => $skillOptions,
            'ownedSkills' => $ownedSkills,
            'team' => $team,
            'currentRequirements' => $currentRequirements,
            'currentOptions' => $currentOptions,
            'wantedPassed' => $wanted,
            'permissions' => [
                'canUpdateTeam' => Gate::check('update', $team),
            ],
        ]);
    }
}
