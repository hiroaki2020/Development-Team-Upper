<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Contracts\Jetstream\InvitesTeamMembers;
use App\Events\InvitingTeamMember;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Mail\TeamInvitation;
use Laravel\Jetstream\Rules\Role;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class InviteTeamMember implements InvitesTeamMembers
{
    /**
     * Invite a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  int  $id
     * @param  string|null  $role
     * @return void
     */
    public function invite($user, $team, int $id, string $role = null, string $message = null)
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $id, $role, $message);

        InvitingTeamMember::dispatch($team, $id, $role, $message);

        $team->teamInvitations()->create([
            'user_id' => $id,
            'role' => $role,
            'message' => $message,
        ]);

    }

    /**
     * Validate the invite member operation.
     *
     * @param  mixed  $team
     * @param  string  $id
     * @param  string|null  $role
     * @return void
     */
    protected function validate($team, int $id, ?string $role, ?string $message)
    {
        Validator::make([
            'id' => $id,
            'role' => $role,
            'message' => $message,
        ], $this->rules($team), [
            'id.unique' => __('This user has already been invited to the team.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $id)
        )->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for inviting a team member.
     *
     * @param  mixed  $team
     * @return array
     */
    protected function rules($team)
    {
        return array_filter([
            'id' => ['required', 'integer', Rule::unique('team_invitations', 'user_id')->where(function ($query) use ($team) {
                $query->where('team_id', $team->id);
            }),
            Rule::in(User::pluck('id'))],
            'role' => Jetstream::hasRoles()
                            ? ['required', 'string', new Role]
                            : null,
            'message' => ['string', 'nullable', 'max:1000']
        ]);
    }

    /**
     * Ensure that the user is not already on the team.
     *
     * @param  mixed  $team
     * @param  string  $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyOnTeam($team, int $id)
    {
        return function ($validator) use ($team, $id) {
            $validator->errors()->addIf(
                $team->hasUserWithId($id),
                'id',
                __('This user already belongs to the team.')
            );
        };
    }
}
