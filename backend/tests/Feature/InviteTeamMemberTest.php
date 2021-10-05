<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Mail\TeamInvitation;
use Tests\TestCase;

class InviteTeamMemberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team owner can invite users to team.
     * 
     * @return void
     */
    public function test_team_owner_can_invite_users_to_team()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());

        $user->load('ownedTeams');
        
        $otherUser = User::factory()->create();

        $response = $this->post('/teams/'.$user->ownedTeams->first()->id.'/members', [
            'id' => $otherUser->id,
            'role' => 'administrator',
            'message'=> 'Hello world!'
        ]);

        $response->assertStatus(303);
        $this->assertCount(1, $user->ownedTeams->first()->load('teamInvitations')->teamInvitations);
    }

    /**
     * Team member in administrator role can invite users to team.
     * 
     * @return void
     */
    public function test_team_member_in_administrator_role_can_invite_users_to_team()
    {
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $otherUser2 = User::factory()->create();
        $team = $user->currentTeam;
        $team->users()->attach($otherUser->id, ['role' => 'administrator']);
        $this->actingAs($otherUser = $otherUser->fresh());

        $response = $this->post('/teams/'.$team->id.'/members', [
            'id' => $otherUser2->id,
            'role' => 'administrator',
            'message'=> 'Hello world!'
        ]);

        $response->assertStatus(303);
        $this->assertCount(1, $team->load('teamInvitations')->teamInvitations);
    }

    /**
     * Team member in editor role can't invite users to team.
     * 
     * @return void
     */
    public function test_team_member_in_editor_role_cant_invite_users_to_team()
    {
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $otherUser2 = User::factory()->create();
        $team = $user->currentTeam;
        $team->users()->attach($otherUser->id, ['role' => 'editor']);
        $this->actingAs($otherUser = $otherUser->fresh());

        $response = $this->post('/teams/'.$team->id.'/members', [
            'id' => $otherUser2->id,
            'role' => 'administrator',
            'message'=> 'Hello world!'
        ]);

        $response->assertStatus(403);
        $this->assertCount(0, $team->load('teamInvitations')->teamInvitations);
    }

    /**
     * Team member in viewer role can't invite users to team.
     * 
     * @return void
     */
    public function test_team_member_in_viewer_role_cant_invite_users_to_team()
    {
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $otherUser2 = User::factory()->create();
        $team = $user->currentTeam;
        $team->users()->attach($otherUser->id, ['role' => 'viewer']);
        $this->actingAs($otherUser = $otherUser->fresh());

        $response = $this->post('/teams/'.$team->id.'/members', [
            'id' => $otherUser2->id,
            'role' => 'administrator',
            'message'=> 'Hello world!'
        ]);

        $response->assertStatus(403);
        $this->assertCount(0, $team->load('teamInvitations')->teamInvitations);
    }
}
