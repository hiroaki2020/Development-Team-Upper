<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\Jetstream\AddsTeamMembers;
use App\Models\JoinRequest;
use Inertia\Inertia;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class HandleJoinRequestController extends Controller
{
    /**
     * Accepting invitations needs authrization.
     * 
     * 
     */
    public function __construct()
    {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $team = auth()->user()->currentTeam;
        if(! Gate::check('addTeamMember', $team)) {
            throw new AuthorizationException(__('You are not authorized to add team members.'));
        }
        $joinRequests = auth()->user()->current_team_id !== null ? $team->joinRequests->load('user') : [];

        return Inertia::render('TeamUp/Requests/Index', [
            'joinRequests' => $joinRequests,
            'canAddTeamMembers' => Gate::check('addTeamMember', $team),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request  $request
     * 
     */
    public function accept(Request $request)
    {
        $join_request = JoinRequest::findOrFail($request->join_request['id']);
        $join_request->load('team', 'user');
        app(AddsTeamMembers::class)->add(
            auth()->user(),
            $join_request->team,
            $join_request->user_id,
            $join_request->role
        );

        $join_request->delete();

        return redirect()->back()->banner(
            __('Great! You have accepted :user\'s request.', ['user' => $join_request->user->name]),
        );
    }

    /**
     * 
     * 
     */
    public function decline(JoinRequest $join_request)
    {
        if(! Gate::check('removeTeamMember', $join_request->team)) {
            throw new AuthorizationException(__('You are not authorized to decline requests.'));
        }

        $join_request->delete();

        return back(303);
    }
}
