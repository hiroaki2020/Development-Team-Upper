<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AcceptTeamInvitationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team inviation can be accepted.
     * 
     * @return void
     */
    public function test_team_invitation_can_be_accepted()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->withTeam()->create();
        $invitation = $otherUser->currentTeam->teamInvitations()->create([
            'user_id' => $user->id,
            'role' => 'administrator',
            'message' => 'Hello world!'
        ]);

        $response = $this->post(route('team-invitations.accept'), [
            'invitation' => $invitation
        ]);

        $response->assertStatus(302)
                ->assertSessionHas('flash.banner');
        $this->assertNull($invitation->fresh());
        $this->assertNotNull($user->fresh()->currentTeam);
    }
}
