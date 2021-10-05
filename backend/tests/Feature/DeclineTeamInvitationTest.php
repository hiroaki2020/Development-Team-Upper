<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TeamInvitation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeclineTeamInvitationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A team invitation can be declined.
     * 
     * @return void
     */
    public function test_team_invitation_can_be_declined()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->withTeam()->create();
        $invitation = $otherUser->currentTeam->teamInvitations()->create([
            'user_id' => $user->id,
            'role' => 'editor',
            'message' => 'Hello world!',
        ]);

        $response = $this->delete(route('invitations.destroy', $invitation));

        $response->assertStatus(303);
        $this->expectException(ModelNotFoundException::class);
        TeamInvitation::findOrFail($invitation->id);
    }
}
