<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class SeeTeamProfileController extends Controller
{
    /**
     * Show team profile.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = Team::findOrFail($id)->load('owner', 'users');
        $requirements = $team->requirements()->where('required', true)->pluck('requirement')->all();
        $options = $team->requirements()->where('required', false)->pluck('requirement')->all();
        $hasAnyUser = !$team->users()->get()->isEmpty();
        $isMember = '';
        $invited = '';
        $requesting = '';
        if(auth()->check()) {
            $isMember = $team->hasUserWithId(auth()->user()->id);
            $invited = auth()->user()->teamInvitations()->where('team_id', $id)->first();
            $requesting = auth()->user()->joinRequests()->where('team_id', $id)->first();
        }
        
        return Inertia::render('TeamProfile/TeamProfile', [
            'team' => $team,
            'requirements' => $requirements,
            'options' => $options,
            'hasAnyUser' => $hasAnyUser,
            'isMember' => $isMember,
            'invited' => $invited,
            'availableRoles' => array_values(Jetstream::$roles),
            'requesting' => $requesting,
        ]);
    }
}
