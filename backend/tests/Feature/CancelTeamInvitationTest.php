<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class CancelTeamInvitationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team owner can cancel team invitation.
     * 
     * @return void
     */
    public function test_team_owner_can_cancel_team_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $invitation = $otherUser->currentTeam->teamInvitations()->create([
            'user_id' => $user->id,
            'role' => 'administrator',
            'message' => 'Hello world!'
        ]);
        $this->actingAs($otherUser = $otherUser->fresh());

        $response = $this->delete(route('team-invitations.destroy', $invitation));

        $response->assertStatus(303);
        $this->assertNull($invitation->fresh());
    }

    /**
     * Team member in administrator role can cancel team invitation.
     * 
     * @return void
     */
    public function test_team_member_in_administrator_role_can_cancel_team_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $invitation = $otherUser->currentTeam->teamInvitations()->create([
            'user_id' => $user->id,
            'role' => 'administrator',
            'message' => 'Hello world!'
        ]);
        $otherUser2 = User::factory()->create();
        $otherUser->currentTeam->users()->attach($otherUser2->id, ['role' => 'administrator']);
        $this->actingAs($otherUser2 = $otherUser2->fresh());

        $response = $this->delete(route('team-invitations.destroy', $invitation));

        $response->assertStatus(303);
        $this->assertNull($invitation->fresh());
    }

    /**
     * Team member in editor role can't cancel team invitation.
     * 
     * @return void
     */
    public function test_team_member_in_editor_role_cant_cancel_team_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $invitation = $otherUser->currentTeam->teamInvitations()->create([
            'user_id' => $user->id,
            'role' => 'administrator',
            'message' => 'Hello world!'
        ]);
        $otherUser2 = User::factory()->create();
        $otherUser->currentTeam->users()->attach($otherUser2->id, ['role' => 'editor']);
        $this->actingAs($otherUser2 = $otherUser2->fresh());

        $response = $this->delete(route('team-invitations.destroy', $invitation));

        $response->assertStatus(403);
        $this->assertNotNull($invitation->fresh());
    }

    /**
     * Team member in viewer role can't cancel team invitation.
     * 
     * @return void
     */
    public function test_team_member_in_viewer_role_cant_cancel_team_invitation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $invitation = $otherUser->currentTeam->teamInvitations()->create([
            'user_id' => $user->id,
            'role' => 'administrator',
            'message' => 'Hello world!'
        ]);
        $otherUser2 = User::factory()->create();
        $otherUser->currentTeam->users()->attach($otherUser2->id, ['role' => 'viewer']);
        $this->actingAs($otherUser2 = $otherUser2->fresh());

        $response = $this->delete(route('team-invitations.destroy', $invitation));

        $response->assertStatus(403);
        $this->assertNotNull($invitation->fresh());
    }
}
