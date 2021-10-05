<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class TeamProfilePhotoController extends Controller
{
    /**
     * Delete the current user's profile photo.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Team $team)
    {
        if(! Gate::check('update', $team)) {
            throw new AuthorizationException(__('You are not authorized to edit team profile.'));
        }
        $team->deleteProfilePhoto();

        return back(303)->with('status', 'team-profile-photo-deleted');
    }
}
