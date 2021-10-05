<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;
use App\Models\SkillOption;
use Illuminate\Support\Facades\Gate;

class TeamUpController extends Controller
{
    public function index(Request $request)
    {
        $skillOptions = SkillOption::orderBy('skill', 'asc')->get();
        $team = auth()->check() && auth()->user()->current_team_id !== null ? auth()->user()->currentTeam : '';
        $joinRequestsCount = auth()->check() && auth()->user()->current_team_id !== null ? auth()->user()->currentTeam->joinRequests->count() : [];
        $teamInvitationsCount = auth()->check() ? auth()->user()->teamInvitations->count() : [];
        return Jetstream::inertia()->render($request, 'TeamUp/TeamUp', [
            'joinRequestsCount' => $joinRequestsCount,
            'teamInvitationsCount' => $teamInvitationsCount,
            'skillOptions' => $skillOptions,
            'canAddTeamMembers' => Gate::check('addTeamMember', $team),
        ]);
    }
}
