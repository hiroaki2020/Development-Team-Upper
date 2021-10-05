<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\JoinRequestInputPost;
use Laravel\Jetstream\Jetstream;
use App\Models\JoinRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class JoinRequestController extends Controller
{
    /**
     * Making requests needs authrization.
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
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JoinRequestInputPost $request)
    {
        $team_id = $request->team_id;
        $role = $request->role;
        $message = $request->message;
        $team = Jetstream::newTeamModel()->findOrFail($team_id);
        $user = auth()->user();

        if($team->hasUserWithId($user->id)) {
            return redirect()->back()->dangerBanner(
                __('You already belong to this team.')
            );
        }
        
        $user->joinRequests()->create([
            'team_id' => $team_id,
            'role' => $role,
            'message' => $message,
        ]);

        return back(303);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Cancel the given join request.
     *
     * @param  \App\Models\JoinRequest  $join_request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(JoinRequest $join_request)
    {
        if(! Gate::check('cancelJoinRequest', $join_request)) {
            throw new AuthorizationException(__('You are not authorized to cancel this request.'));
        }
        $join_request->delete();

        return back(303);
    }
}
