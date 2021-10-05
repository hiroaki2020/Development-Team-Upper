<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use App\Contracts\Jetstream\AddsTeamMembers;
use App\Models\TeamInvitation;
use App\Models\Team;

class TeamInvitationController extends Controller
{
    /**
     * Accept a team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Jetstream\TeamInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request)
    {
        $invitation = TeamInvitation::findOrFail($request->invitation['id']);
        if(! Gate::check('handleTeamInvitation', $invitation)) {
            throw new AuthorizationException(__('You are not authorized to accept this invitation.'));
        }
        
        $invitation->load('team.owner');
        app(AddsTeamMembers::class)->add(
            $invitation->team->owner,
            $invitation->team,
            $invitation->user_id,
            $invitation->role
        );

        $invitation->delete();

        return redirect()->back()->banner(
            __('Great! You have accepted the invitation to join the :team.', ['team' => $invitation->team->name]),
        );
    }

    /**
     * Cancel the given team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Jetstream\TeamInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, TeamInvitation $invitation)
    {
        if (! Gate::forUser($request->user())->check('removeTeamMember', $invitation->team)) {
            throw new AuthorizationException;
        }

        $invitation->delete();

        return back(303);
    }
}
