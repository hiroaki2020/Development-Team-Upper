<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class InvitingTeamMember
{
    use Dispatchable;

    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

    /**
     * The email address of the invitee.
     *
     * @var int
     */
    public int $id;

    /**
     * The role of the invitee.
     *
     * @var mixed
     */
    public $role;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $team
     * @param  mixed  $email
     * @param  mixed  $role
     * @return void
     */
    public function __construct($team, int $id, $role)
    {
        $this->team = $team;
        $this->id = $id;
        $this->role = $role;
    }
}
