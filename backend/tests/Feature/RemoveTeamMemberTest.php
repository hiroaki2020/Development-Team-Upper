<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemoveTeamMemberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team owner can remove team members from team.
     * 
     * @return void
     */
    public function test_team_owner_can_remove_team_members_from_team()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'administrator']
        );

        $conversation = Conversation::create(['team_id' => $user->currentTeam->id]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id);

        $response->assertStatus(303);
        $this->assertCount(0, $user->currentTeam->fresh()->users);
    }

    /**
     * Team member in administrator role can remove team members from team.
     * 
     * @return void
     */
    public function test_team_member_in_administrator_role_can_remove_team_members_from_team()
    {
        $user = User::factory()->withTeam()->create();
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'administrator']
        );
        $user->currentTeam->users()->attach(
            $otherUser2 = User::factory()->create(), ['role' => 'viewer']
        );
        $conversation = Conversation::create(['team_id' => $user->currentTeam->id]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id,
            $otherUser2->id
        ]);
        $this->actingAs($otherUser = $otherUser->fresh());

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$otherUser2->id);

        $response->assertStatus(303);
        $this->assertCount(1, $user->currentTeam->fresh()->users);
    }

    /**
     * Team member in editor role can't remove team members from team.
     * 
     * @return void
     */
    public function test_team_member_in_editor_role_cant_remove_team_members_from_team()
    {
        $user = User::factory()->withTeam()->create();
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'editor']
        );
        $user->currentTeam->users()->attach(
            $otherUser2 = User::factory()->create(), ['role' => 'viewer']
        );
        $conversation = Conversation::create(['team_id' => $user->currentTeam->id]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id,
            $otherUser2->id
        ]);
        $this->actingAs($otherUser);

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$otherUser2->id);

        $response->assertStatus(403);
        $this->assertCount(2, $user->currentTeam->fresh()->users);
    }

    /**
     * Team member in viewer role can't remove team members from team.
     * 
     * @return void
     */
    public function test_team_member_in_viewer_role_cant_remove_team_members_from_team()
    {
        $user = User::factory()->withTeam()->create();
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'viewer']
        );
        $user->currentTeam->users()->attach(
            $otherUser2 = User::factory()->create(), ['role' => 'viewer']
        );
        $conversation = Conversation::create(['team_id' => $user->currentTeam->id]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id,
            $otherUser2->id
        ]);
        $this->actingAs($otherUser);

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$otherUser2->id);

        $response->assertStatus(403);
        $this->assertCount(2, $user->currentTeam->fresh()->users);
    }

    /**
     * Team owner can't remove himself or herself from team.
     * 
     * @return void
     */
    public function test_team_owner_cant_remove_himself_or_herself_from_team()
    {
        $user = User::factory()->withTeam()->create();
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'administrator']
        );
        $conversation = Conversation::create(['team_id' => $user->currentTeam->id]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($user);

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$user->id);

        $response->assertStatus(302);
        $this->assertCount(1, $user->currentTeam->fresh()->users);
    }

    /**
     * Team member in administrator role can't remove team owner from team.
     * 
     * @return void
     */
    public function test_team_member_in_administrator_role_cant_remove_team_owner_from_team()
    {
        $user = User::factory()->withTeam()->create();
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'administrator']
        );
        $conversation = Conversation::create(['team_id' => $user->currentTeam->id]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($otherUser = $otherUser->fresh());

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$user->id);

        $response->assertStatus(302);
        $this->assertCount(1, $user->currentTeam->fresh()->users);
    }

    /**
     * Team member in editor role can't remove team owner from team.
     * 
     * @return void
     */
    public function test_team_member_in_editor_role_cant_remove_team_owner_from_team()
    {
        $user = User::factory()->withTeam()->create();
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'editor']
        );
        $conversation = Conversation::create(['team_id' => $user->currentTeam->id]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($otherUser);

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$user->id);

        $response->assertStatus(403);
        $this->assertCount(1, $user->currentTeam->fresh()->users);
    }

    /**
     * Team member in viewer role can't remove team owner from team.
     * 
     * @return void
     */
    public function test_team_member_in_viewer_role_cant_remove_team_owner_from_team()
    {
        $user = User::factory()->withTeam()->create();
        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'viewer']
        );
        $conversation = Conversation::create(['team_id' => $user->currentTeam->id]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($otherUser);

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$user->id);

        $response->assertStatus(403);
        $this->assertCount(1, $user->currentTeam->fresh()->users);
    }
}
