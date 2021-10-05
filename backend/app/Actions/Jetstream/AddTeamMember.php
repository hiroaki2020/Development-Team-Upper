<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Contracts\Jetstream\AddsTeamMembers;
use Laravel\Jetstream\Events\AddingTeamMember;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Rules\Role;
use App\Models\Conversation;
use Illuminate\Auth\Access\AuthorizationException;

class AddTeamMember implements AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    public function add($user, $team, int $id, string $role = null)
    {
        if(! Gate::forUser($user)->check('addTeamMember', $team)) {
            throw new AuthorizationException(__('You are not authorized to add team members.'));
        }

        $this->validate($team, $id, $role);

        $newTeamMember = Jetstream::findUserByIdOrFail($id);

        AddingTeamMember::dispatch($team, $newTeamMember);

        $team->users()->attach(
            $newTeamMember, ['role' => $role]
        );

        TeamMemberAdded::dispatch($team, $newTeamMember);

        $this->addToTeamChat($newTeamMember, $team);
    }

    /**
     * Add new team member to team chat.
     *
     * @param  mixed  $user
     * @return void
     */
    protected function addToTeamChat($newTeamMember, $team)
    {
        if($team->allUsers()->count() === 1) {
            $conversation = Conversation::create([
                'team_id' => $team->id
            ]);
            $owner_id = $team->owner->id;
            $conversation->users()->attach([
                $owner_id, $newTeamMember->id
            ]);
        } else {
            $conversation = $team->conversation()->first()->load('users');
            $conversation->users()->attach([
                $newTeamMember->id
            ]);
        }
    }

    /**
     * Validate the add member operation.
     *
     * @param  mixed  $team
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    protected function validate($team, int $id, ?string $role)
    {
        Validator::make([
            'id' => $id,
            'role' => $role,
        ], $this->rules(), [
            'id.exists' => __('We were unable to find a registered user with this ID.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $id)
        )->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for adding a team member.
     *
     * @return array
     */
    protected function rules()
    {
        return array_filter([
            'id' => ['required', 'integer', 'exists:users'],
            'role' => Jetstream::hasRoles()
                            ? ['required', 'string', new Role]
                            : null,
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
