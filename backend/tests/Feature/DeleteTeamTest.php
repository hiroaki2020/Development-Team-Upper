<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team owner can delete team.
     * 
     * @return void
     */
    public function test_team_owner_can_delete_team()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());

        $team = $user->ownedTeams->first();

        $response = $this->delete('/teams/'.$team->id);

        $response->assertStatus(302);
        $this->assertNull($team->fresh());
        $this->assertCount(0, $user->fresh()->teams);
    }

    /**
     * Team member in administrator role can't delete team.
     * 
     * @return void
     */
    public function test_team_member_in_administrator_role_cant_delete_team()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $team = $otherUser->ownedTeams->first();
        $team->users()->attach($user->id, ['role' => 'administrator']);
        $this->actingAs($user = $user->fresh());
        
        $response = $this->delete('/teams/'.$team->id);

        $response->assertStatus(403);
        $this->assertNotNull($team->fresh());
    }

    /**
     * Team member in editor role can't delete team.
     * 
     * @return void
     */
    public function test_team_member_in_editor_role_cant_delete_team()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $team = $otherUser->ownedTeams->first();
        $team->users()->attach($user->id, ['role' => 'editor']);
        $this->actingAs($user = $user->fresh());
        
        $response = $this->delete('/teams/'.$team->id);

        $response->assertStatus(403);
        $this->assertNotNull($team->fresh());
    }

    /**
     * Team member in viewer role can't delete team.
     * 
     * @return void
     */
    public function test_team_member_in_viewer_role_cant_delete_team()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $team = $otherUser->ownedTeams->first();
        $team->users()->attach($user->id, ['role' => 'viewer']);
        $this->actingAs($user = $user->fresh());
        
        $response = $this->delete('/teams/'.$team->id);

        $response->assertStatus(403);
        $this->assertNotNull($team->fresh());
    }
}
